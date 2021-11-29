import 'package:fituska_web_app/models/course.dart';
import 'package:fituska_web_app/providers/applications.dart';
import 'package:fituska_web_app/providers/auth.dart';
import 'package:fituska_web_app/providers/cours_prov.dart';
import 'package:fituska_web_app/providers/users.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import 'package:fituska_web_app/widgets/appbar.dart';

class CourseRegScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    var auth = Provider.of<Auth>(context);
    Provider.of<CoursProv>(context).getCourses();
    var courses = Provider.of<CoursProv>(context).courses;
    List<Course> c = [];

    String name = Provider.of<Users>(context).findById(auth.id).name;

    for (var element in courses) {
      if (element.teacher != auth.id) {
        c.add(element);
      }
    }

    var screen = SafeArea(
      child: Scaffold(
        appBar: buildAppBar(context),
        body: Center(
          child: Column(
            children: <Widget>[
              const Padding(
                padding: EdgeInsets.all(8.0),
                child: Text("Registrace do kurzů",
                    style: TextStyle(
                      fontSize: 40.0,
                      fontWeight: FontWeight.bold,
                    )),
              ),
              const SizedBox(
                height: 10,
              ),
              Expanded(
                child: ListView.builder(
                  itemCount: c.length,
                  itemBuilder: (ctx, i) {
                    return Card(
                      margin: const EdgeInsets.symmetric(
                          horizontal: 15, vertical: 4),
                      child: Column(
                        mainAxisSize: MainAxisSize.min,
                        children: <Widget>[
                          ListTile(
                            leading: Icon(Icons.import_contacts),
                            title: Text(c[i].name),
                          ),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.end,
                            children: <Widget>[
                              IconButton(
                                  onPressed: () {
                                    Provider.of<Applications>(context,
                                            listen: false)
                                        .sendAppli(c[i].id, auth);
                                  },
                                  icon: Icon(Icons.arrow_forward))
                            ],
                          ),
                        ],
                      ),
                    );
                  },
                ),
              ),
            ],
          ),
        ),
      ),
    );
    return screen;
  }
}
