import 'package:flutter/material.dart';

class LoginScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    var screen = SafeArea(child: Scaffold (
        body: Center(
            child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: <Widget>[
            Padding(
              padding: const EdgeInsets.all(36.0),
              child: new Text(
                "Log in",
                style:
                    new TextStyle(fontSize: 40.0, fontWeight: FontWeight.bold),
              ),
            ),]
    ))));
    return screen;
  }
}