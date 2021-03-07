package lasitha.trackme;


import android.content.Intent;
import android.net.Uri;
import android.support.v7.app.AppCompatActivity;

import android.os.Bundle;



public class MainActivity extends AppCompatActivity {

  @Override
    protected void onCreate(Bundle savedInstanceState){
      super.onCreate(savedInstanceState);
      setContentView(R.layout.activity_main);

      Intent browsIntent = new Intent(Intent.ACTION_VIEW, Uri.parse("https://sltctrackme.000webhostapp.com"));
      startActivity(browsIntent);
    System.exit(0);
  }
    }