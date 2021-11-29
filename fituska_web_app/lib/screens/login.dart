import 'dart:async';

import 'package:fituska_web_app/providers/auth.dart';
import 'package:fituska_web_app/screens/user_page.dart';
import 'package:fituska_web_app/widgets/appbar.dart';
import 'package:flutter/material.dart';

import 'package:provider/provider.dart';

class LoginScreen extends StatelessWidget {
  final Map<String, String> _authData = {
    'email': '',
    'password': '',
  };

  final _form = GlobalKey<FormState>();

  Future<void> _submit(BuildContext context) async {
    final isValid = _form.currentState!.validate();
    if (!isValid) {
      return;
    } else {
      _form.currentState!.save();
      try {
        await Provider.of<Auth>(context, listen: false).signin(
            _authData["email"], _authData["password"]);
        Navigator.of(context).pushNamed("/");
      } on TimeoutException catch (e) {
        _showErrorDialog(context, e.toString());
      } catch (error) {
        _showErrorDialog(context, error.toString());
      }
    }
  }

  void _showErrorDialog(BuildContext ctx, String message) {
    showDialog(
        context: ctx,
        builder: (ctx) => AlertDialog(
              title: Text("Nastala chyba"),
              content: Text(message),
              actions: [
                FlatButton(
                    onPressed: () {
                      Navigator.of(ctx).pop();
                    },
                    child: Text("Jasně, chápu"))
              ],
            ));
  }

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
                          if (value != null) {
                            if (RegExp(
                                    r"^[a-zA-Z0-9.a-zA-Z0-9.!#$%&'*+-/=?^_`{|}~]+@[a-zA-Z0-9]+\.[a-zA-Z]+")
                                .hasMatch(value)) {
                              return null;
                            }
                          }
                          return "Není email";
                        },
                        decoration: const InputDecoration(
                            labelText: "Email",
                            labelStyle: TextStyle(
                                color: Colors.lightBlue, fontSize: 12.0)),
                        onSaved: (value) {
                          _authData['email'] = value!;
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
                          _submit(context);
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
    final auth = Provider.of<Auth>(context, listen: true);
    // Show user screen if logged in
    return auth.isAuth ? ProfilePage() : screen;
  }
}
