import 'package:fituska_web_app/models/user.dart';
import 'package:flutter/foundation.dart';
import '../models/thread.dart';

class Course {
  int id;
  String name;
  int teacher;
  int approvedBy;
  DateTime created;
  DateTime approved;

  List<Thread> threads = [];

  Course({
    this.id = 0, this.name = "", this.teacher = -1, this.approvedBy = -1, required this.threads, required this.created, required this.approved,
  });
}
