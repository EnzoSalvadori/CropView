from flask import Flask, render_template, request, redirect, url_for, session

app = Flask(__name__)
app.secret_key = "segredo"  # necessário para usar session

@app.route("/")
@app.route("/inicio")
@app.route("/home")
def index():
	try:
		if session["lang"] == "pt":
			return render_template("index1.html")
		if session["lang"] == "en":
			return render_template("index2.html")
		if session["lang"] == "es":
			return render_template("index3.html")
	except:
		return render_template("index1.html")

@app.route("/requisitos")
@app.route("/requirements")
def requisitos():
	try:
		if session["lang"] == "pt":
			return render_template("requirements1.html")
		if session["lang"] == "en":
			return render_template("requirements2.html")
		if session["lang"] == "es":
			return render_template("requirements3.html")
	except:
		return render_template("requirements1.html")

@app.route("/precios")
@app.route("/precos")
@app.route("/pricing")
def preços():
	try:
		if session["lang"] == "pt":
			return render_template("pricing1.html")
		if session["lang"] == "en":
			return render_template("pricing2.html")
		if session["lang"] == "es":
			return render_template("pricing3.html")
	except:
		return render_template("pricing1.html")

@app.route("/contato")
@app.route("/contacto")
@app.route("/contact")
def contato():
	try:
		if session["lang"] == "pt":
			return render_template("contactUs1.html")
		if session["lang"] == "en":
			return render_template("contactUs2.html")
		if session["lang"] == "es":
			return render_template("contactUs3.html")
	except:
		return render_template("contactUs1.html")

@app.route("/set_language", methods=["POST"])
def set_language():
	lang = request.form.get("language")
	if lang:
		session["lang"] = lang  # salva na sessão
	print(session["lang"])
	# volta para a página anterior, ou index se não tiver referrer
	return redirect(request.referrer or url_for("index"))


if __name__ == "__main__":
	app.run(debug=True)
