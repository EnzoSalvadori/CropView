import unittest
from html.parser import HTMLParser
from pathlib import Path
from urllib.parse import urljoin, urlsplit

from index import app


class PageParser(HTMLParser):
    def __init__(self, html):
        super().__init__()
        self.language = None
        self.links = []
        self.assets = []
        self.feed(html)

    def handle_starttag(self, tag, attrs):
        attrs = dict(attrs)
        if tag == "html":
            self.language = attrs.get("lang")
        if tag == "a" and "href" in attrs:
            self.links.append(attrs["href"])
        if "src" in attrs:
            self.assets.append(attrs["src"])
        if tag == "link" and "href" in attrs:
            self.assets.append(attrs["href"])


class LanguageTests(unittest.TestCase):
    routes = (
        "/", "/inicio", "/home", "/requisitos", "/requirements",
        "/precios", "/precos", "/pricing", "/contato", "/contacto", "/contact",
    )

    def setUp(self):
        app.config["TESTING"] = True
        self.client = app.test_client()

    def test_pages_links_and_assets_in_each_language(self):
        root = Path(app.root_path)
        for language, html_language in (("pt", "pt-BR"), ("en", "en"), ("es", "es")):
            self.client.post("/set_language", data={"language": language})
            for route in self.routes:
                with self.subTest(language=language, route=route):
                    response = self.client.get(route)
                    self.assertEqual(response.status_code, 200)
                    page = PageParser(response.get_data(as_text=True))
                    self.assertEqual(page.language, html_language)
                    for link in page.links:
                        if link.startswith("#") or urlsplit(link).scheme:
                            continue
                        target = urljoin(route, link)
                        linked_page = self.client.get(target)
                        self.assertEqual(linked_page.status_code, 200, target)
                        self.assertEqual(PageParser(linked_page.get_data(as_text=True)).language, html_language)
                    for asset in page.assets:
                        if not urlsplit(asset).scheme:
                            self.assertTrue((root / asset.lstrip("/")).is_file(), asset)

    def test_language_switch_returns_to_current_page(self):
        for route in ("/inicio", "/requisitos", "/precos", "/contato"):
            for language in ("pt", "en", "es"):
                with self.subTest(route=route, language=language):
                    response = self.client.post(
                        "/set_language", data={"language": language},
                        headers={"Referer": "http://localhost" + route},
                    )
                    self.assertEqual(response.status_code, 302)
                    self.assertEqual(urlsplit(response.location).path, route)
                    with self.client.session_transaction() as session:
                        self.assertEqual(session["lang"], language)

    def test_default_and_invalid_stored_language_use_portuguese(self):
        for language in (None, "invalid"):
            with self.client.session_transaction() as session:
                session.clear()
                if language is not None:
                    session["lang"] = language
            for route in self.routes:
                with self.subTest(language=language, route=route):
                    response = self.client.get(route)
                    self.assertEqual(response.status_code, 200)
                    self.assertEqual(PageParser(response.get_data(as_text=True)).language, "pt-BR")

    def test_invalid_or_missing_language_preserves_selection(self):
        for initial in (None, "en", "es"):
            with self.client.session_transaction() as session:
                session.clear()
                if initial:
                    session["lang"] = initial
            for payload in ({}, {"language": ""}, {"language": "invalid"}):
                with self.subTest(initial=initial, payload=payload):
                    response = self.client.post("/set_language", data=payload, follow_redirects=True)
                    self.assertEqual(response.status_code, 200)
                    with self.client.session_transaction() as session:
                        self.assertEqual(session.get("lang"), initial)

    def test_contact_details_in_every_language(self):
        for language in ("pt", "en", "es"):
            self.client.post("/set_language", data={"language": language})
            html = self.client.get("/contato").get_data(as_text=True)
            page = PageParser(html)
            self.assertIn("mailto:enzocropview@gmail.com", page.links)
            self.assertIn("https://api.whatsapp.com/send?phone=5542998650083", page.links)
            self.assertIn("+55 (42) 99865-0083", html)


if __name__ == "__main__":
    unittest.main()
