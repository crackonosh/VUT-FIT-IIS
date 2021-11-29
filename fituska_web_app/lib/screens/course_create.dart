import 'dart:convert';

import 'package:fituska_web_app/providers/auth.dart';
import 'package:fituska_web_app/screens/login.dart';
import 'package:fituska_web_app/widgets/appbar.dart';

import 'package:http/http.dart' as http;

import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class CourseCreate extends StatelessWidget {
  final Map<String, String> _formData = {
    'code': '',
    'name': '',
  };

  final api = "164.68.102.86";

  final _form = GlobalKey<FormState>();

  Future<void> _submit(BuildContext context, Auth auth) async {
    final isValid = _form.currentState!.validate();
    if (!isValid) {
      return;
    }
    _form.currentState!.save();
    final Uri url = Uri.parse("http://$api:8000/courses/add");
    String b =
        jsonEncode({"code": _formData["code"], 'name': _formData['name']});
    try {
      final response = await http
          .post(
        url,
        headers: {
          'Content-type': 'application/json',
          'Authorization': 'Bearer ${auth.token}',
        },
        body: b,
      )
          .timeout(const Duration(seconds: 4), onTimeout: () {
        throw Exception("Timed out");
      });
      final res = json.decode(response.body);
      if (res['message'] != "Successfully created new course.") {
        throw Exception(res["message"]);
      }
      Navigator.of(context).pushNamed("/user-panel");
    } catch (error) {
      _showErrorDialog(context, error.toString());
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
    final auth = Provider.of<Auth>(context);
    var screen = SafeArea(
      child: Scaffold(
        appBar: buildAppBar(context),
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.start,
            children: <Widget>[
              const Padding(
                padding: EdgeInsets.all(36.0),
                child: Text(
                  "Vytvoření kurzu",
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
                              return "Musí být vyplněno";
                            }
                            return null;
                          },
                          decoration: const InputDecoration(
                              labelText: "Kód",
                              labelStyle: TextStyle(
                                  color: Colors.lightBlue, fontSize: 12.0)),
                          onSaved: (value) {
                            _formData['code'] = value!;
                          },
                        ),
                        TextFormField(
                          validator: (value) {
                            if (value!.length < 2) {
                              return "Název musí být delší";
                            }
                            return null;
                          },
                          obscureText: true,
                          decoration: const InputDecoration(
                              labelText: "Jméno",
                              labelStyle: TextStyle(
                                  color: Colors.lightBlue, fontSize: 12.0)),
                          onSaved: (value) {
                            _formData['name'] = value!;
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
                            _submit(context, auth);
                          },
                          child: Container(
                            alignment: Alignment.center,
                            height: 48.0,
                            decoration: BoxDecoration(
                                color: Colors.blueAccent,
                                borderRadius: BorderRadius.circular(40.0)),
                            child: const Text("Založit",
                                style: TextStyle(
                                    color: Colors.white,
                                    fontSize: 16.0,
                                    fontWeight: FontWeight.bold)),
                          )),
                    ),
                  )
                ],
              ),
            ],
          ),
        ),
      ),
    );
    return auth.isAuth ? screen : LoginScreen();
  }
}
