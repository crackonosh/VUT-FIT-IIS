import 'dart:async';

import 'package:fituska_web_app/providers/auth.dart';
import 'package:fituska_web_app/providers/courses.dart';
import 'package:fituska_web_app/providers/users.dart';
import 'package:fituska_web_app/widgets/appbar.dart';
import 'package:fituska_web_app/widgets/course_item.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class FituskaStart extends StatefulWidget {
  @override
  _FituskaStartState createState() => _FituskaStartState();
}

class _FituskaStartState extends State<FituskaStart> {
  var _isInit = true;
  var _isLoading = false;

  Future<void> _update(BuildContext context) async {
    try {
      Provider.of<Courses>(context, listen: false).initCourses();
    } catch (e) {
      _showErrorDialog(context, e.toString());
    }
  }

  @override
  void didChangeDependencies() {
    if (_isInit) {
      Provider.of<Auth>(context, listen: false).tryAutoLogin();
      Provider.of<Users>(context, listen: false).initUsers();
      const tim = const Duration(seconds: 30);
      //Timer.periodic(tim, (timer) => _update(context));
      setState(() {
        _isLoading = true;
      });
      _isInit = false;
      Provider.of<Courses>(context, listen: false).initCourses().then((_) => setState(() {
                  _isLoading = false;
                }));
    }
    super.didChangeDependencies();
  }

  void _showErrorDialog(BuildContext ctx, String message) {
    showDialog(
        context: ctx,
        builder: (ctx) => AlertDialog(
              title: Text("Nastala chyba"),
              content: Text(message),
              actions: [
                FlatButton(
                    onPressed: () {
                      Navigator.of(ctx).pop();
                    },
                    child: Text("Jasně, chápu"))
              ],
            ));
  }

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
                Expanded(
                child: _isLoading
                  ? Center(child: CircularProgressIndicator(),) :
                    ListView.builder(
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
