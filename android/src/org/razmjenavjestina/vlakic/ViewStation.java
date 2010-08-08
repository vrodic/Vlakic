package org.razmjenavjestina.vlakic;

import java.util.ArrayList;
import java.util.Iterator;

import android.app.Activity;
import android.content.Intent;
import android.database.Cursor;
import android.graphics.Color;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;
import android.widget.TableLayout.LayoutParams;



public class ViewStation extends Activity {
	private static final int ACTIVITY_ADDDEST=0;
	
	private TimetableDbAdapter mDbHelper;
	public static final int ADDDEST_ID = Menu.FIRST;
	private String stationId;
	private String toStation;
	private String fromStation;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.view_station);

		mDbHelper = new TimetableDbAdapter(this);
		mDbHelper.open();

		Bundle extras = getIntent().getExtras();
		stationId = extras.getString("_id");
		toStation = extras.getString("tostation");
		fromStation = extras.getString("fromstation");
		Cursor c = null;
		ArrayList l = null;
		if (stationId != null) {
			setTitle(extras.getString("name"));
			c = mDbHelper.getStationTimetable(stationId);
		} else {
			setTitle(mDbHelper.getStationName(fromStation) + " - "
					+ extras.getString("toname"));
			l = mDbHelper.getStationToFromTimetable(toStation, fromStation);
		}

		if (c != null) {
			fillDataCursor(c);

		} else {
			fillDataArray(l);
			mDbHelper.updateOrInsertLastToFrom(toStation, fromStation);
		}

	}
    
    private void fillDataArray(ArrayList l) {
   	 TableLayout tl = (TableLayout)findViewById(R.id.view_station);
        tl.setColumnShrinkable(2, true);
        Iterator iterator = l.iterator();
        
        int i =0;
        while (iterator.hasNext())         
        {
        	   TableRow tr = new TableRow(this);
        	   if (i % 2 != 0) {
        		   tr.setBackgroundColor(Color.DKGRAY);
        	   }
        	   //tr.set
        	   
               tr.setId(100+i);
             ArrayList in = (ArrayList) iterator.next();
             Iterator<String> initer = in.iterator();
             int j = 0;
             while (initer.hasNext()) {  
        		String txt =  initer.next();
        		if (j == 0) {
        			txt = txt.substring(0,5);
        		}
        		 TextView tv = new TextView(this);
        		 //System.out.println("Adding " + c.getString(j));
                 tv.setId(200+i+j);
                 tv.setText(txt);
                 
                 tv.setPadding(0, 0, 10, 0);                
                 //tv.set
                 //tv.setLayoutParams(new LayoutParams(LayoutParams.WRAP_CONTENT,LayoutParams.WRAP_CONTENT ));
                //labelTV.setTextColor(Color.BLACK);
            
                 tr.addView(tv);
                 j++;
        	}
   

            // Add the TableRow to the TableLayout
            tl.addView(tr, new TableLayout.LayoutParams(
                    LayoutParams.FILL_PARENT,
                    LayoutParams.WRAP_CONTENT));        
            i++;
            
       
        }        
   }
    
    private void fillDataCursor(Cursor c) {
    	 TableLayout tl = (TableLayout)findViewById(R.id.view_station);
         tl.setColumnShrinkable(2, true);
         
         // Go through each item in the array
         c.moveToFirst();
         int i =0;
         while (c.isAfterLast() == false)         
         {
         	   TableRow tr = new TableRow(this);
         	   if (i % 2 != 0) {
         		   tr.setBackgroundColor(Color.DKGRAY);
         	   }
         	   //tr.set
         	   
                tr.setId(100+i);
              boolean doAdd = true;
         	for (int j= 0; j < c.getColumnCount(); j++) {
         		String txt = c.getString(j);
         		if (txt.length()< 5) {
         			doAdd = false;
         			continue;
         		}
         		if (j == 0 ) {
         			txt = txt.substring(0,5);
         		}
         		 TextView tv = new TextView(this);
         		 //System.out.println("Adding " + c.getString(j));
                  tv.setId(200+i+j);
                  tv.setText(txt);
                  
                  tv.setPadding(0, 0, 10, 0);                
                  //tv.set
                  //tv.setLayoutParams(new LayoutParams(LayoutParams.WRAP_CONTENT,LayoutParams.WRAP_CONTENT ));
                 //labelTV.setTextColor(Color.BLACK);
             
                  tr.addView(tv);
         	}
    
         	if(doAdd) {
             // Add the TableRow to the TableLayout
             tl.addView(tr, new TableLayout.LayoutParams(
                     LayoutParams.FILL_PARENT,
                     LayoutParams.WRAP_CONTENT));
         	}
             c.moveToNext();
             i++;
             
        
         }
         c.close();
    }
    
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        boolean result = super.onCreateOptionsMenu(menu);
        if (stationId != null) {
        menu.add(0, ADDDEST_ID, 0, R.string.adddest);
        }
        return result;
    }
    
    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
        case ADDDEST_ID:
            addDestination();
            return true;
        }
       
        return super.onOptionsItemSelected(item);
    }
    
    public void addDestination() {
    	Intent i = new Intent(this, vlakic.class);
        i.putExtra("fromstation",stationId);
        
   
        startActivityForResult(i, ACTIVITY_ADDDEST);	
    }
    
}
