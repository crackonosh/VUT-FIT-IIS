import 'package:fituska_web_app/providers/auth.dart';
import 'package:fituska_web_app/providers/users.dart';
import 'package:fituska_web_app/widgets/message_item.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import 'package:fituska_web_app/providers/courses.dart';
import 'package:fituska_web_app/widgets/appbar.dart';

class ThreadDetailScreen extends StatelessWidget {
  static const routeName = "/thread-detail";

  @override
  Widget build(BuildContext context) {
    var auth = Provider.of<Auth>(context);
    var args = ModalRoute.of(context)!.settings.arguments as Map;
    var course = Provider.of<Courses>(context).findById(args["courseId"]);
    var thread =
        course.threads.firstWhere((element) => element.id == args["id"]);
    var author = Provider.of<Users>(context).findById(thread.author);
    var screen = SafeArea(
      child: Scaffold(
        appBar: buildAppBar(context),
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.start,
            children: <Widget>[
              Padding(
                padding: EdgeInsets.all(8.0),
                child: Text(thread.title,
                    style: TextStyle(
                      fontSize: 40.0,
                      fontWeight: FontWeight.bold,
                    )),
              ),
              SizedBox(
                height: 10,
              ),
              Card(
                margin: const EdgeInsets.symmetric(horizontal: 15, vertical: 4),
                child: ListTile(
                  leading: Icon(Icons.menu_book),
                  title: Text("Autor"),
                  subtitle: Text(author.name),
                ),
              ),
              Expanded(
                  child: ListView.builder(
                      itemCount: thread.messages.length,
                      itemBuilder: (ctx, i) => MessageItem(
                          thread.messages[i].text,
                          thread.messages[i].role,
                          thread.messages[i].author))),
            ],
          ),
        ),
        floatingActionButton: Visibility(
          visible: auth.isAuth,
          child: FloatingActionButton(
            child: Icon(Icons.add),
            onPressed: () {
              //Navigator.of(context).pushNamed("/newTransaction");
            },
          ),
        ),
      ),
    );
    return screen;
  }
}
