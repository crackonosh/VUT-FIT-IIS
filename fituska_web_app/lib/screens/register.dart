import 'package:flutter/material.dart';

class RegisterScreen extends StatelessWidget {
  final Map<String, String> _authData = {
    'login': '',
    'password': '',
  };

  final _form = GlobalKey<FormState>();

  @override
  Widget build(BuildContext context) {
    var screen = SafeArea(
      child: Scaffold(
        appBar: AppBar(
          automaticallyImplyLeading: false,
          title: TextButton(
              onPressed: () => Navigator.of(context).pushNamed("/"),
              style: TextButton.styleFrom(
                primary: Colors.white,
              ),
              child: const Text(
                "Fituška 2.0",
                style: TextStyle(fontSize: 25),
              )),
          actions: <Widget>[
            TextButton(
                onPressed: () =>
                    Navigator.of(context).pushNamed("/leaderboard"),
                style: TextButton.styleFrom(
                  primary: Colors.white,
                ),
                child: const Text(
                  "Žebříček",
                  style: TextStyle(fontSize: 25),
                )),
            IconButton(
                onPressed: () => Navigator.of(context).pushNamed("/login"),
                icon: const Icon(
                  Icons.account_circle,
                  size: 25,
                )),
          ],
        ),
        body: Center(
            child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: <Widget>[
            const Padding(
              padding: EdgeInsets.all(36.0),
              child: Text("Registrace",
                  style:
                      TextStyle(fontSize: 40.0, fontWeight: FontWeight.bold)),
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
                            return "Heslo musí mít 8 a více znaků";
                          }
                          return null;
                        },
                        obscureText: true,
                        decoration: const InputDecoration(
                            labelText: "Heslo",
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
                        onTap: () {},
                        child: Container(
                          alignment: Alignment.center,
                          height: 48.0,
                          decoration: BoxDecoration(
                              color: Colors.blueAccent,
                              borderRadius: BorderRadius.circular(40.0)),
                          child: const Text("Vytvořit účet",
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
                Navigator.pop(context);
              },
              child: const Text("zpátky",
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
