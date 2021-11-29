import 'package:flutter/material.dart';

import 'package:fituska_web_app/widgets/appbar.dart';

class CourseRegScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    var screen = SafeArea(
      child: Scaffold(
        appBar: buildAppBar(context),
        
      ),
    );
    return screen;
  }
}
