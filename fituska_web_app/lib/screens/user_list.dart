import 'package:fituska_web_app/providers/users.dart';
import 'package:fituska_web_app/widgets/appbar.dart';
import 'package:fituska_web_app/widgets/user.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class UserList extends StatelessWidget {
  static const routeName = "/user_managment";
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
                  child: Text("Uživatelé",
                      style: TextStyle(
                          fontSize: 40.0, fontWeight: FontWeight.bold))),
              const SizedBox(
                height: 10,
              ),
              Expanded(
                  child: ListView.builder(
                      itemCount: users.length,
                      itemBuilder: (ctx, i) {
                        return UserCardList(users[i].id, users[i].name);
                      })),
            ],
          ),
        ),
      ),
    );
    return screen;
  }
}
