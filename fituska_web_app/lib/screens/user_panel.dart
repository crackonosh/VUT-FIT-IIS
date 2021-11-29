import 'package:fituska_web_app/providers/auth.dart';
import 'package:fituska_web_app/screens/login.dart';
import 'package:fituska_web_app/widgets/appbar.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class UserPanel extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    var screen = SafeArea(
      child: Scaffold(
        appBar: buildAppBar(context),
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.start,
            children: <Widget>[
              TextButton(onPressed: () => Navigator.of(context).pushNamed("/course-create"), child: const Text("Registrovat na kurz")),
              TextButton(onPressed: () => Navigator.of(context).pushNamed("/course-create"), child: const Text("Vytvořit kurz")),
              TextButton(onPressed: () => Navigator.of(context).pushNamed("/course-create"), child: const Text("Spravovat kurzy")),
              TextButton(onPressed: () => Navigator.of(context).pushNamed("/course-create"), child: const Text("Vytvořit kurz")),
              TextButton(onPressed: () => Navigator.of(context).pushNamed("/course-create"), child: const Text("Vytvořit kurz")),
              TextButton(onPressed: () => Navigator.of(context).pushNamed("/course-create"), child: const Text("Vytvořit kurz")),
              TextButton(onPressed: () => Navigator.of(context).pushNamed("/course-create"), child: const Text("Vytvořit kurz")),
            ],
          ),
        ),
      ),
    );

    final auth = Provider.of<Auth>(context, listen: true);
    // Show user screen if logged in
    return auth.isAuth ? screen : LoginScreen();
  }
}
