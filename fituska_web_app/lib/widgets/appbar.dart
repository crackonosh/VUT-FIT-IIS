import 'package:fituska_web_app/providers/auth.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

AppBar buildAppBar(BuildContext context) {
  final auth = Provider.of<Auth>(context);
  return AppBar(
    automaticallyImplyLeading: false,
    backgroundColor: Colors.blueAccent,
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
      /*(
          onPressed: () => Navigator.of(context).pushNamed("/leaderboard"),
          style: TextButton.styleFrom(
            primary: Colors.white,
          ),
          child: const Text(
            "Žebříček",
            style: TextStyle(fontSize: 25),
          )),*/
      IconButton(
          onPressed: () => Navigator.of(context).pushNamed("/login"),
          icon: const Icon(
            Icons.account_circle,
            size: 25,
          )),
      if (auth.isAuth)
        TextButton(
          onPressed: () => Navigator.of(context).pushNamed("/user-panel"),
            style: TextButton.styleFrom(
              primary: Colors.white,
            ),
            child: const Text(
              "Možnosti",
              style: TextStyle(fontSize: 25),
        )),
    ],
  );
}
