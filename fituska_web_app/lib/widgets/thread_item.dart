import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import 'package:fituska_web_app/providers/users.dart';
import 'package:fituska_web_app/screens/thread_detail.dart';

class ThreadItem extends StatelessWidget {
  final String courseId;
  final int id;
  final String title;
  final bool isClosed;
  final int author;
  final String category;

  ThreadItem(this.courseId, this.id, this.title, this.isClosed, this.author,
      this.category);

  @override
  Widget build(BuildContext context) {
    final auth = Provider.of<Users>(context).findById(author);
    var args = {"courseId": courseId, "id": id};
    return Card(
      color: isClosed ? Colors.grey : Colors.white,
      margin: const EdgeInsets.symmetric(horizontal: 15, vertical: 4),
      child: Padding(
        padding: const EdgeInsets.all(8),
        child: GestureDetector(
          onTap: () {
            Navigator.of(context)
                .pushNamed(ThreadDetailScreen.routeName, arguments: args);
          },
          child: ListTile(
            leading: const Icon(Icons.message_outlined),
            title: Text(/*"$title - $category"*/ title),
            subtitle: Text(auth.name),
          ),
        ),
      ),
    );
  }
}
