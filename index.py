from flask import Flask, render_template, request, redirect, url_for, session

app = Flask(__name__)
app.secret_key = "segredo"  # necessário para usar session

LANGUAGE_SUFFIXES = {"pt": "1", "en": "2", "es": "3"}


def render_localized_page(page):
	suffix = LANGUAGE_SUFFIXES.get(session.get("lang"), "1")
	return render_template(f"{page}{suffix}.html")


@app.route("/")
@app.route("/inicio")
@app.route("/home")
def index():
	return render_localized_page("index")

@app.route("/requisitos")
@app.route("/requirements")
def requisitos():
	return render_localized_page("requirements")

@app.route("/precios")
@app.route("/precos")
@app.route("/pricing")
def preços():
	return render_localized_page("pricing")

@app.route("/contato")
@app.route("/contacto")
@app.route("/contact")
def contato():
	return render_localized_page("contactUs")

@app.route("/set_language", methods=["POST"])
def set_language():
	lang = request.form.get("language")
	if lang in LANGUAGE_SUFFIXES:
		session["lang"] = lang  # salva na sessão
	# volta para a página anterior, ou index se não tiver referrer
	return redirect(request.referrer or url_for("index"))


if __name__ == "__main__":
	app.run(debug=True)
