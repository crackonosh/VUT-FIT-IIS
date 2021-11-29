import 'package:fituska_web_app/providers/applications.dart';
import 'package:fituska_web_app/providers/auth.dart';
import 'package:fituska_web_app/providers/categories.dart';
import 'package:fituska_web_app/providers/courses.dart';
import 'package:fituska_web_app/providers/users.dart';
import 'package:flutter/material.dart';
import 'package:fituska_web_app/widgets/appbar.dart';
import 'package:provider/provider.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class CourseEditScreen extends StatelessWidget {
  static const routeName = "/course-edit";

  final Map<String, String> _catData = {
    'name': '',
  };

  final _form = GlobalKey<FormState>();

  Future<void> _sendCategory(
      BuildContext context, String code, Auth auth) async {
    final isValid = _form.currentState!.validate();
    if (!isValid) {
      return;
    } else {
      _form.currentState!.save();
      try {
        await Provider.of<Categories>(context, listen: false)
            .addCat(code, _catData["name"] as String, auth);
      } catch (error) {
        _showErrorDialog(context, error.toString());
      }
    }
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
    String code = ModalRoute.of(context)!.settings.arguments as String;
    var courses = Provider.of<Courses>(context);
    var course = Provider.of<Courses>(context).findById(code);
    var auth = Provider.of<Auth>(context);
    Provider.of<Applications>(context).getAppli(code, auth);
    var apPro = Provider.of<Applications>(context);
    var appli = apPro.applications;
    Provider.of<Categories>(context).getCats(code);
    var catPro = Provider.of<Categories>(context);
    var catt = catPro.categories;

    var screen = SafeArea(
      child: Scaffold(
        appBar: buildAppBar(context),
        body: Center(
          child: Column(mainAxisAlignment: MainAxisAlignment.start, children: <
              Widget>[
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
                  margin:
                      const EdgeInsets.symmetric(horizontal: 15, vertical: 4),
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    children: <Widget>[
                      ListTile(
                        leading: Icon(Icons.category),
                        title: Text(appli[i].student),
                        trailing: Chip(
                          label: appli[i].approved
                              ? Text("Přístup")
                              : Text("NEpřístup"),
                          backgroundColor:
                              appli[i].approved ? Colors.green : Colors.red,
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
                              icon: Icon(Icons.close)),
                        ],
                      ),
                    ],
                  ),
                ),
              ),
            ),
            Text("Kategorie:",
                style: TextStyle(
                  fontSize: 25.0,
                  fontWeight: FontWeight.bold,
                )),
            Expanded(
              child: ListView.builder(
                itemCount: catt.length,
                itemBuilder: (ctx, i) {
                  return Card(
                    margin:
                        const EdgeInsets.symmetric(horizontal: 15, vertical: 4),
                    child: Column(
                      mainAxisSize: MainAxisSize.min,
                      children: <Widget>[
                        ListTile(
                          leading: Icon(Icons.category),
                          title: Text(catt[i].name),
                          subtitle: Text(catt[i].id.toString()),
                        ),
                      ],
                    ),
                  );
                },
              ),
            ),
            Form(
              key: _form,
              child: Column(
                children: <Widget>[
                  TextFormField(
                    textInputAction: TextInputAction.next,
                    validator: (value) {
                      if (value!.isEmpty) {
                        return "Nemůže být prázdné";
                      }
                      return null;
                    },
                    decoration: const InputDecoration(
                        labelText: "Název",
                        labelStyle:
                            TextStyle(color: Colors.lightBlue, fontSize: 12.0)),
                    onSaved: (value) {
                      _catData['name'] = value!;
                    },
                  ),
                  TextButton(
                      onPressed: () {
                        _sendCategory(context, code, auth);
                      },
                      child: const Text("Přidat")),
                ],
              ),
            ),
            Text(
              "Otázky",
              style: TextStyle(
                fontSize: 25.0,
                fontWeight: FontWeight.bold,
              ),
            ),
            Expanded(
              child: ListView.builder(
                  itemCount: course.threads.length,
                  itemBuilder: (ctx, i) {
                    final au = Provider.of<Users>(ctx)
                        .findById(course.threads[i].author);
                    return Card(
                      margin: const EdgeInsets.symmetric(
                          horizontal: 15, vertical: 4),
                      child: Column(
                        mainAxisSize: MainAxisSize.min,
                        children: <Widget>[
                          ListTile(
                            leading:
                                Icon(Icons.label_important_outline_rounded),
                            title: Text(course.threads[i].title),
                            subtitle: Text(au.name),
                            trailing: Chip(
                              label: course.threads[i].isClosed
                                  ? Text("Zamknuto")
                                  : Text("Odemknuto"),
                              backgroundColor: course.threads[i].isClosed
                                  ? Colors.grey
                                  : Colors.white,
                            ),
                          ),
                          if (!course.threads[i].isClosed)
                            Row(
                              mainAxisAlignment: MainAxisAlignment.end,
                              children: <Widget>[
                                IconButton(
                                    onPressed: () {
                                      courses.setClosed(course.id,
                                          course.threads[i].id, auth);
                                    },
                                    icon: Icon(Icons.lock))
                              ],
                            )
                        ],
                      ),
                    );
                  }),
            ),
          ]),
        ),
      ),
    );

    return screen;
  }
}
