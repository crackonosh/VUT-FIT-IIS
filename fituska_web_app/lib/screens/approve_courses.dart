import 'package:fituska_web_app/models/course_application.dart';
import 'package:fituska_web_app/providers/app_courses.dart';
import 'package:fituska_web_app/providers/auth.dart';
import 'package:fituska_web_app/providers/courses.dart';
import 'package:flutter/material.dart';
import 'package:fituska_web_app/widgets/appbar.dart';
import 'package:provider/provider.dart';
import 'dart:convert';

class ApproveCoursesScreen extends StatelessWidget {
  static const routeName = "/course-management";

  @override
  Widget build(BuildContext context) {
    //String code = ModalRoute.of(context)!.settings.arguments as String;
    var auth = Provider.of<Auth>(context);
    Provider.of<Course_Applications>(context).getAppli(auth);
    var apPro = Provider.of<Course_Applications>(context);
    var appli = apPro.applications;

    var screen = SafeArea(
      child: Scaffold(
        appBar: buildAppBar(context),
        body: Center(
          child: Column(
              mainAxisAlignment: MainAxisAlignment.start,
              children: <Widget>[
                SizedBox(
                  height: 10,
                ),
                Text("Seznam neschválených kruzů:",
                    style: TextStyle(
                      fontSize: 25.0,
                      fontWeight: FontWeight.bold,
                    )),
                Expanded(
                  child: ListView.builder(
                    itemCount: appli.length,
                    itemBuilder: (ctx, i) => Card(
                      margin: const EdgeInsets.symmetric(
                          horizontal: 15, vertical: 4),
                      child: Column(
                        mainAxisSize: MainAxisSize.min,
                        children: <Widget>[
                          ListTile(
                            leading: Icon(Icons.school),
                            title: Text(appli[i].course),
                            trailing: Chip(
                              label: Text("Neschváleno"),
                              backgroundColor: Colors.red,
                            ),
                          ),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.end,
                            children: <Widget>[
                              IconButton(
                                  onPressed: () =>
                                      {apPro.setAccept(appli[i].code, auth)},
                                  icon: Icon(Icons.check)),
                              IconButton(
                                  onPressed: () =>
                                      {apPro.setDecline(appli[i].code, auth)},
                                  icon: Icon(Icons.close))
                            ],
                          ),
                        ],
                      ),
                    ),
                  ),
                ),
              ]),
        ),
      ),
    );

    return screen;
  }
}
