import 'package:fituska_web_app/providers/courses.dart';
import 'package:fituska_web_app/widgets/appbar.dart';
import 'package:fituska_web_app/widgets/course_item.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class FituskaStart extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final courseData = Provider.of<Courses>(context);
    var courses = courseData.courses;
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
                Expanded(child: ListView.builder( 
                  itemCount: courses.length,
                  itemBuilder: (ctx, i) => CourseItem(
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
