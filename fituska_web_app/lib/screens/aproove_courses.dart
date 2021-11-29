import 'package:fituska_web_app/providers/auth.dart';
import 'package:fituska_web_app/providers/courses.dart';
import 'package:flutter/material.dart';
import 'package:fituska_web_app/widgets/appbar.dart';
import 'package:provider/provider.dart';
import 'dart:convert';

class AprooveCoursesScreen extends StatelessWidget {
  static const routeName = "/course-edit";

  @override
  Widget build(BuildContext context) {
    

    var screen = SafeArea(
      child: Scaffold(
        appBar: buildAppBar(context),
        body: Center(
          child: Column(
              mainAxisAlignment: MainAxisAlignment.start,
              children: <Widget>[
                Padding(
                  padding: EdgeInsets.all(8.0),
                  child: Text(course.name,
                      style: TextStyle(
                        fontSize: 40.0,
                        fontWeight: FontWeight.bold,
                      )),
                ),
                SizedBox(
                  height: 10,
                ),
                Text("Studenti co se chtějí přidat:",
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
                            leading: Icon(Icons.messenger_outline_sharp),
                            title: Text(appli[i].student),
                            trailing: Chip(
                              label: Text("Nepovoleno"),
                              backgroundColor: Colors.red,
                            ),
                          ),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.end,
                            children: <Widget>[
                              IconButton(
                                  onPressed: () =>
                                      {apPro.setAccept(appli[i].id, code, auth)},
                                  icon: Icon(Icons.check)),
                              IconButton(
                                  onPressed: () =>
                                      {apPro.setDecline(appli[i].id, code, auth)},
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
