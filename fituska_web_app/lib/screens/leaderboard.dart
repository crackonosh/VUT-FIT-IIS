import 'package:fituska_web_app/providers/users.dart';
import 'package:fituska_web_app/widgets/appbar.dart';
import 'package:fituska_web_app/widgets/user_card.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class LeaderboardScreen extends StatelessWidget {
  int j = 0;
  @override
  Widget build(BuildContext context) {
    final users = Provider.of<Users>(context).users;
    var screen = SafeArea(
      child: Scaffold(
        appBar: buildAppBar(context),
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.start,
            children: <Widget>[
              const Padding(
                  padding: EdgeInsets.all(8.0),
                  child: Text("Žebříček",
                      style: TextStyle(
                          fontSize: 40.0, fontWeight: FontWeight.bold))),
              const SizedBox(
                height: 10,
              ),
              Expanded(
                  child: ListView.builder(
                      itemCount: users.length,
                      itemBuilder: (ctx, i) {
                        j++;
                        return UserCard(j, users[i].name, users[i].score);
                      })),
            ],
          ),
        ),
      ),
    );
    return screen;
  }
}
