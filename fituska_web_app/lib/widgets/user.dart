import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import 'package:fituska_web_app/providers/users.dart';
import 'package:fituska_web_app/screens/user_managment.dart';

class UserCardList extends StatelessWidget {
  final int id;
  final String name;

  UserCardList(this.id, this.name);

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 15, vertical: 4),
      child: Padding(
        padding: const EdgeInsets.all(8),
        child: GestureDetector(
          onTap: () {
            Navigator.of(context)
                .pushNamed(ManageUser.routeName, arguments: id);
          },
          child: ListTile(leading: Text(id.toString()), title: Text(name)),
        ),
      ),
    );
  }
}
