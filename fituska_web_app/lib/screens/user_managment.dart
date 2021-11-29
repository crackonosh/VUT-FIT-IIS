import 'package:flutter/material.dart';
import 'package:fituska_web_app/widgets/appbar.dart';
import 'package:fituska_web_app/providers/auth.dart';
import 'package:fituska_web_app/providers/users.dart';
import 'package:fituska_web_app/providers/role.dart';
import 'package:provider/provider.dart';
import 'package:http/http.dart' as http;

class ManageUser extends StatelessWidget {
  
  String dropdownValue = 'User';

  final Map<String, int> mapRole = {
    'Admin': 1,
    'Mod': 2,
    'User' : 3,
  };

  final _form = GlobalKey<FormState>();

  Future<void> _update(BuildContext context, int id, int role, Auth auth) async {
    try {
      await Provider.of<ChangeRole>(context, listen: false).changeId(id, role, auth);
      Navigator.of(context).pushNamed("/");
    } catch (error) {
      _showErrorDialog(context, error.toString());
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
    final auth = Provider.of<Auth>(context);
    int id = ModalRoute.of(context)!.settings.arguments as int;
    var user = Provider.of<Users>(context).findById(id);

    var screen = SafeArea(
        child: Scaffold(
          appBar: buildAppBar(context),
        body: Center(
            child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: <Widget>[
            const Padding(
              padding: EdgeInsets.all(36.0),
              child: Text(
                "Správa uživatele",
                style: TextStyle(fontSize: 40.0, fontWeight: FontWeight.bold),
              ),
            ),
            Padding(
                padding: const EdgeInsets.only(left: 98.0, right: 98.0),
                child: Form(
                  key: _form,
                  child: Column(
                    children: <Widget>[
                      Text(
                        user.email,
                        style: const TextStyle(
                                color: Colors.lightBlue, fontSize: 12.0),
                      ),
                      Text(
                        user.name,
                        style: const TextStyle(
                                color: Colors.lightBlue, fontSize: 12.0),
                      ),
                      DropdownButton<String>(
                        value: dropdownValue,
                        icon: const Icon(Icons.arrow_downward),
                        iconSize: 24,
                        elevation: 16,
                        style: const TextStyle(color: Colors.blueAccent),
                        underline: Container(
                          height: 2,
                          color: Colors.blueAccent
                        ),
                        onChanged: (String? newValue) {
                          setState(() {
                            dropdownValue = newValue!;
                          });
                        },
                        items: <String>['Admin', 'Mod', 'User']
                            .map<DropdownMenuItem<String>>((String value) {
                    ],
                  ),
                )),
        ),
      ),
      Row(
              children: <Widget>[
                Expanded(
                  child: Padding(
                    padding: const EdgeInsets.only(
                        left: 24.0, top: 48.0, right: 24.0, bottom: 8.0),
                    child: GestureDetector(
                        onTap: () {
                          _update(user.id, mapRole[dropdownValue], auth)
                        },
                        child: Container(
                          alignment: Alignment.center,
                          height: 48.0,
                          decoration: BoxDecoration(
                              color: Colors.blueAccent,
                              borderRadius: BorderRadius.circular(40.0)),
                          child: const Text("Upravit",
                              style: TextStyle(
                                  color: Colors.white,
                                  fontSize: 16.0,
                                  fontWeight: FontWeight.bold)),
                        )),
                  ),
                )
              ],
            );
    return screen;
  }
}
