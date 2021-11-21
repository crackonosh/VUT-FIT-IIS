import 'package:flutter/material.dart';

class LeaderboardScreen extends StatelessWidget {
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
                    onPressed: () => Navigator.of(context).pushNamed("/"),
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
