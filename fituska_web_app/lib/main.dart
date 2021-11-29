import 'package:fituska_web_app/providers/applications.dart';
import 'package:fituska_web_app/providers/categories.dart';
import 'package:fituska_web_app/screens/add_thread.dart';
import 'package:fituska_web_app/screens/course_create.dart';
import 'package:fituska_web_app/screens/course_detail.dart';
import 'package:fituska_web_app/screens/course_edit.dart';
import 'package:fituska_web_app/screens/course_reg.dart';
import 'package:fituska_web_app/screens/my_courses.dart';
import 'package:fituska_web_app/screens/thread_detail.dart';
import 'package:fituska_web_app/screens/user_panel.dart';
import 'package:fituska_web_app/screens/approve_courses.dart';
import 'package:fituska_web_app/screens/user_managment.dart';
import 'package:fituska_web_app/screens/user_list.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import 'providers/auth.dart';

import 'providers/courses.dart';
import 'providers/users.dart';
import 'providers/app_courses.dart';
import 'providers/role.dart';
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
        ChangeNotifierProvider<Courses>(
          create: (_) => Courses(),
        ),
        ChangeNotifierProvider<Users>(
          create: (_) => Users(),
        ),
        ChangeNotifierProvider<Applications>(
          create: (_) => Applications(),
        ),
        ChangeNotifierProvider<Course_Applications>(
          create: (_) => Course_Applications(),
        ),
        ChangeNotifierProvider<ChangeRole>(
          create: (_) => ChangeRole(),
        ),
        ChangeNotifierProvider<Categories>(
          create: (_) => Categories(),
        ),
      ],
      child: MaterialApp(
        title: "Fituška",
        routes: {
          "/": (ctx) => FituskaStart(),
          "/login": (ctx) => LoginScreen(),
          "/register": (ctx) => RegisterScreen(),
          "/leaderboard": (ctx) => LeaderboardScreen(),
          "/user": (ctx) => ProfilePage(),
          "/course-detail": (ctx) => CourseDetailScreen(),
          "/thread-detail": (ctx) => ThreadDetailScreen(),
          "/course-create": (ctx) => CourseCreate(),
          "/user-panel": (ctx) => UserPanel(),
          "/add-thread": (ctx) => AddThreadScreen(),
          "/course-list": (ctx) => MyCourseList(),
          "/course-edit": (ctx) => CourseEditScreen(),
          "/course-management": (ctx) => ApproveCoursesScreen(),
          "/user-management": (ctx) => ManageUser(),
          "/user-list": (ctx) => UserList(),
          "/course-reg": (ctx) => CourseRegScreen(),
        },
      ),
    );
  }
}
