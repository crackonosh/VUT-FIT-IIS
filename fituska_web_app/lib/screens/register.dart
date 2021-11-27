import 'dart:async';

import 'package:fituska_web_app/screens/fituska.dart';
import 'package:flutter/material.dart';

import 'package:provider/provider.dart';

import 'package:fituska_web_app/widgets/appbar.dart';
import 'package:fituska_web_app/providers/auth.dart';

class RegisterScreen extends StatelessWidget {
  final Map<String, String> _authData = {
    'login': '',
    'password': '',
    'email': '',
  };

  final _form = GlobalKey<FormState>();

  Future<void> _submit(BuildContext context) async {
    final isValid = _form.currentState!.validate();
    if (!isValid) {
      return;
    } else {
      _form.currentState!.save();
      print(_authData['login']);
      print(_authData['password']);
      try {
        await Provider.of<Auth>(context, listen: false).signup(
            _authData["login"], _authData["password"], _authData["email"]);
      } catch (error) {}
    }
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
                      TextFormField(
                        decoration: const InputDecoration(
                            labelText: "Email",
                            labelStyle: TextStyle(
                                color: Colors.lightBlue, fontSize: 12.0)),
                        onSaved: (value) {
                          _authData['email'] = value!;
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
    final auth = Provider.of<Auth>(context, listen: true);
    //return auth.isAuth ? FituskaStart() : screen;
    return screen;
  }
}
