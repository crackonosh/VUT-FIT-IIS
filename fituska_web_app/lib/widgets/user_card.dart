import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import 'package:fituska_web_app/providers/users.dart';
import 'package:fituska_web_app/screens/thread_detail.dart';

class UserCard extends StatelessWidget {
  final int id;
  final String name;
  final int score;

  UserCard(this.id, this.name, this.score);

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 15, vertical: 4),
      child: Padding(
        padding: const EdgeInsets.all(8),
        child: ListTile(
            leading: Text(id.toString()),
            title: Text(/*"$title - $category"*/ name),
            subtitle: Text(score.toString()),
        ),
      )
    );
  }
}
