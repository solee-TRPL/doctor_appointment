import 'package:doctor_appointment/components/doctor_card.dart';
import 'package:doctor_appointment/models/auth_model.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class FavPage extends StatefulWidget {
  const FavPage({super.key});

  @override
  State<FavPage> createState() => _FavPageState();
}

class _FavPageState extends State<FavPage> {
  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Padding(
        padding: EdgeInsets.only(left: 20, top: 20, right: 20),
        child: Column(
          children: [
            Text(
              'My Fovorite Doctors',
              textAlign: TextAlign.center,
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            SizedBox(height: 20),
            Expanded(
              child: Consumer<AuthModel>(
                builder: (context, auth, child) {
                  return ListView.builder(
                  itemCount: auth.getFavDoc.length,
                  itemBuilder: (context, Index) {
                    return DoctorCard(
                      doctor: auth.getFavDoc[Index],
                      isFav: true,
                    );
                  },
                );
                },
                // child: 
              ),
            ),
          ],
        ),
      ),
    );
  }
}
