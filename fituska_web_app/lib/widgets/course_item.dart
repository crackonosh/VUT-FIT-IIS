import 'package:provider/provider.dart';
import 'package:flutter/material.dart';
import 'package:fituska_web_app/providers/users.dart';
import 'package:fituska_web_app/screens/course_detail.dart';

class CourseItem extends StatelessWidget {
  final String id;
  final String title;
  final int teacher;
  final int nbrOfThreads;

  CourseItem(this.id, this.title, this.teacher, this.nbrOfThreads);

  @override
  Widget build(BuildContext context) {
    final teach = Provider.of<Users>(context).findById(teacher);
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 15, vertical: 4),
      child: Padding(
        padding: const EdgeInsets.all(8),
        child: GestureDetector(
          onTap: () {
            Navigator.of(context)
                .pushNamed(CourseDetailScreen.routeName, arguments: id);
          },
          child: ListTile(
            leading: Text(id),
            title: Text(title),
            subtitle: Text(teach.name),
          ),
        ),
      ),
    );
  }
}
