import 'package:fituska_web_app/models/course.dart';
import 'package:fituska_web_app/models/user.dart';

class StudentApplication {
  int id;
  String student;
  bool approved;

  StudentApplication({this.id = -1, this.student = "", this.approved = false});
}
