import 'package:fituska_web_app/providers/auth.dart';
import 'package:fituska_web_app/screens/login.dart';
import 'package:fituska_web_app/widgets/appbar.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class UserPanel extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final auth = Provider.of<Auth>(context);
    var screen = SafeArea(
      child: Scaffold(
        appBar: buildAppBar(context),
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.start,
            children: <Widget>[
              TextButton(
                  onPressed: () =>
                      Navigator.of(context).pushNamed("/course-create"),
                  child: const Text("Registrovat na kurz")),
              TextButton(
                  onPressed: () =>
                      Navigator.of(context).pushNamed("/course-create"),
                  child: const Text("Vytvořit kurz")),
              TextButton(
                  onPressed: () =>
                      Navigator.of(context).pushNamed("/course-list"),
                  child: const Text("Spravovat kurzy")),
              if (auth.role == "admin" || auth.role == "moderator")
                TextButton(
                    onPressed: () =>
                        Navigator.of(context).pushNamed("/course-management"),
                    child: const Text("Spravovat kurzy - ADMIN")),
              if (auth.role == "admin")
                TextButton(
                    onPressed: () =>
                        Navigator.of(context).pushNamed("/user-list"),
                    child: const Text("Spravovat Uživatele")),
            ],
          ),
        ),
      ),
    );
    return auth.isAuth ? screen : LoginScreen();
  }
}
