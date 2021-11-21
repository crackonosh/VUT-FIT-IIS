import 'package:fituska_web_app/widgets/appbar.dart';
import 'package:flutter/material.dart';

class LoginScreen extends StatelessWidget {
  final Map<String, String> _authData = {
    'login': '',
    'password': '',
  };

  final _form = GlobalKey<FormState>();

  @override
  Widget build(BuildContext context) {
    var screen = SafeArea(
      child: Scaffold(
        appBar: buildAppBar(context),
        body: Center(
            child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: <Widget>[
            const Padding(
              padding: EdgeInsets.all(36.0),
              child: Text(
                "Přihlášení",
                style: TextStyle(fontSize: 40.0, fontWeight: FontWeight.bold),
              ),
            ),
            Padding(
                padding: const EdgeInsets.only(left: 98.0, right: 98.0),
                child: Form(
                  key: _form,
                  child: Column(
                    children: <Widget>[
                      TextFormField(
                        textInputAction: TextInputAction.next,
                        validator: (value) {
                          if (value!.isEmpty) {
                            return "Pole nesmí být prázdné";
                          }
                          return null;
                        },
                        decoration: const InputDecoration(
                            labelText: "Login",
                            labelStyle: TextStyle(
                                color: Colors.lightBlue, fontSize: 12.0)),
                        onSaved: (value) {
                          _authData['login'] = value!;
                        },
                      ),
                      TextFormField(
                        validator: (value) {
                          if (value!.length < 8) {
                            return "Heslo musí mít á a více znaků";
                          }
                          return null;
                        },
                        obscureText: true,
                        decoration: const InputDecoration(
                            labelText: "Password",
                            labelStyle: TextStyle(
                                color: Colors.lightBlue, fontSize: 12.0)),
                        onSaved: (value) {
                          _authData['password'] = value!;
                        },
                      ),
                    ],
                  ),
                )),
            Row(
              children: <Widget>[
                Expanded(
                  child: Padding(
                    padding: const EdgeInsets.only(
                        left: 24.0, top: 48.0, right: 24.0, bottom: 8.0),
                    child: GestureDetector(
                        onTap: () {
                          Navigator.of(context).pushNamed("/user");
                        },
                        child: Container(
                          alignment: Alignment.center,
                          height: 48.0,
                          decoration: BoxDecoration(
                              color: Colors.blueAccent,
                              borderRadius: BorderRadius.circular(40.0)),
                          child: const Text("Přihlásit se",
                              style: TextStyle(
                                  color: Colors.white,
                                  fontSize: 16.0,
                                  fontWeight: FontWeight.bold)),
                        )),
                  ),
                )
              ],
            ),
            GestureDetector(
              onTap: () {
                Navigator.of(context).pushNamed("/register");
              },
              child: const Text("Registrace",
                  style: TextStyle(
                      color: Colors.grey,
                      fontSize: 12.0,
                      decoration: TextDecoration.underline)),
            )
          ],
        )),
      ),
    );
    return screen;
  }
}
