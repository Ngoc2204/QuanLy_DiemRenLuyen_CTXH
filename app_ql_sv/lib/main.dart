import 'package:flutter/material.dart';
import 'screens/splash_screen.dart';

void main() {
  runApp(DiemRenLuyenApp());
}

class DiemRenLuyenApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Điểm Rèn Luyện',
      debugShowCheckedModeBanner: false,
      home: SplashScreen(),
    );
  }
}
