import 'package:fituska_web_app/models/course.dart';
import 'package:fituska_web_app/providers/auth.dart';
import 'package:fituska_web_app/providers/courses.dart';
import 'package:fituska_web_app/widgets/my_course_item.dart';
import 'package:flutter/material.dart';
import 'package:fituska_web_app/widgets/appbar.dart';
import 'package:provider/provider.dart';

class MyCourseList extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final courses = Provider.of<Courses>(context).courses;
    final auth = Provider.of<Auth>(context);
    List<Course> myCourses = [];
    courses.forEach((element) {
      if (element.teacher == auth.id) {
        myCourses.add(element);
      }
    });
    var screen = SafeArea(
        child: Scaffold(
            appBar: buildAppBar(context),
            body: Center(
                child: Column(
              mainAxisAlignment: MainAxisAlignment.start,
              children: <Widget>[
                Padding(
                  padding: EdgeInsets.all(8.0),
                  child: Text("Kurzy",
                      style: TextStyle(
                        fontSize: 40.0,
                        fontWeight: FontWeight.bold,
                      )),
                ),
                SizedBox(
                  height: 10,
                ),
                Expanded(
                  child: ListView.builder(
                  itemCount: myCourses.length,
                  itemBuilder: (ctx, i) => MyCourseItem(
                      courses[i].id,
                      courses[i].name,
                      courses[i].teacher,
                      courses[i].threads.length),
                )),
              ],
            ))));
    return screen;
  }
}
