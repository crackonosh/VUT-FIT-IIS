import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import 'providers/auth.dart';

import 'screens/fituska.dart';
import 'screens/login.dart';
import 'screens/register.dart';
import 'screens/leaderboard.dart';
import 'screens/user_page.dart';

void main() => runApp(Fituska());

class Fituska extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider<Auth>(
          create: (_) => Auth(),
        ),
      ],
      child: MaterialApp(
        title: "Fituška",
        routes: {
          "/": (ctx) => FituskaStart(),
          "/login": (ctx) => LoginScreen(),
          "/register": (ctx) => RegisterScreen(),
          "/leaderboard": (ctx) => LeaderboardScreen(),
          "/user": (ctx) => ProfilePage()
        },
      ),
    );
  }
}
