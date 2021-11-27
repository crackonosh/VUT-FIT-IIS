import 'package:fituska_web_app/widgets/appbar.dart';
import 'package:flutter/material.dart';

class LeaderboardScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    var screen = SafeArea(
        child: Scaffold(
            appBar: buildAppBar(context),
            body: Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.start,
                children: const <Widget>[
                  Padding(
                      padding: EdgeInsets.all(8.0),
                      child: Text("Žebříček",
                          style: TextStyle(
                              fontSize: 40.0, fontWeight: FontWeight.bold)))
                ],
              ),
            )));
    return screen;
  }
}
