import 'package:fituska_web_app/providers/auth.dart';
import 'package:fituska_web_app/providers/users.dart';
import 'package:fituska_web_app/widgets/thread_item.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import 'package:fituska_web_app/providers/courses.dart';
import 'package:fituska_web_app/widgets/appbar.dart';

class CourseDetailScreen extends StatelessWidget {
  static const routeName = "/course-detail";

  @override
  Widget build(BuildContext context) {
    String id = ModalRoute.of(context)!.settings.arguments as String;
    final auth = Provider.of<Auth>(context);
    final course = Provider.of<Courses>(context, listen: false).findById(id);
    final teach =
        Provider.of<Users>(context, listen: false).findById(course.teacher);
    var screen = SafeArea(
      child: Scaffold(
        appBar: buildAppBar(context),
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.start,
            children: <Widget>[
              Padding(
                padding: const EdgeInsets.all(8.0),
                child: Text(
                  course.name,
                  style: TextStyle(
                    fontSize: 40.0,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
              SizedBox(
                height: 10,
              ),
              Card(
                margin: const EdgeInsets.symmetric(horizontal: 15, vertical: 4),
                child: ListTile(
                  leading: Icon(Icons.menu_book),
                  title: Text("Vyučující"),
                  subtitle: Text(teach.name),
                ),
              ),
              Card(
                margin: const EdgeInsets.symmetric(horizontal: 15, vertical: 4),
                child: ListTile(
                  leading: Icon(Icons.forum_outlined),
                  title: Text(
                    "Témata",
                    style: TextStyle(fontSize: 35.0),
                  ),
                ),
              ),
              Expanded(
                child: ListView.builder(
                    itemCount: course.threads.length,
                    itemBuilder: (ctx, i) => ThreadItem(
                        course.id,
                        course.threads[i].id,
                        course.threads[i].title,
                        course.threads[i].isClosed,
                        course.threads[i].author,
                        course.threads[i].category)),
              ),
            ],
          ),
        ),
        floatingActionButton: Visibility(
          visible: auth.isAuth,
          child: FloatingActionButton(
            child: Icon(Icons.add),
            onPressed: () {
              Navigator.of(context).pushNamed("/add-thread", arguments: id);
            },
          ),
        ),
      ),
    );

    return screen;
  }
}
