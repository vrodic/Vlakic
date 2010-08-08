package org.razmjenavjestina.vlakic;

import android.app.ListActivity;
import android.content.Intent;
import android.database.Cursor;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.ContextMenu;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ContextMenu.ContextMenuInfo;
import android.widget.EditText;
import android.widget.ListView;
import android.widget.SimpleCursorAdapter;
import android.widget.AdapterView.AdapterContextMenuInfo;

public class vlakic extends ListActivity {
	private static final int ACTIVITY_VIEWSTATION=0;
	private static final int ACTIVITY_GOTOSELECTIONS=1;
	public static final int LAST_ID = Menu.FIRST;
	private static final int ADDFAV_ID = Menu.FIRST + 1;


    private EditText ed;
    private Cursor mCursor;
    
	
	private TimetableDbAdapter mDbHelper;
	

	
	private String fromstation; // this is filled if ViewStation activity calls us to select 
								// a destination station

	/** Called when the activity is first created. */
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.station_list);
		mDbHelper = new TimetableDbAdapter(this);
		mDbHelper.open();
		
		ed=(EditText)findViewById(R.id.EditText01);


		ed.addTextChangedListener(new TextWatcher() {

			public void afterTextChanged(Editable s) {
			}

			public void beforeTextChanged(CharSequence s, int start, int count,
					int after) {
			}

			public void onTextChanged(CharSequence s, int start, int before,
					int count) {
				fillData(ed.getText().toString());

			}
		});

		registerForContextMenu(getListView());	
		Bundle extras = getIntent().getExtras();
		
		fromstation = null;
		String force = null;
		if (extras != null ) {
			force = extras.getString("force");
		fromstation = extras.getString("fromstation");
		if (fromstation == null) {
			//System.out.println("Fromstation is null!");
		} else {
			//System.out.println("Fromstation is not null!");
			setTitle(R.string.adddest);
		}
		}
		if (mDbHelper.hasSelections() && force == null && fromstation == null) {
			Intent i = new Intent(this, Selections.class);      	       
	        startActivityForResult(i, ACTIVITY_GOTOSELECTIONS);	
		} else {
			fillData(null);		
		}
	}
    
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        boolean result = super.onCreateOptionsMenu(menu);
        menu.add(0, LAST_ID, 0, R.string.menu_last);
        return result;
    }    

    @Override
	public void onCreateContextMenu(ContextMenu menu, View v,
			ContextMenuInfo menuInfo) {
		super.onCreateContextMenu(menu, v, menuInfo);
        menu.add(0, ADDFAV_ID, 0, R.string.menu_addfav);
	}
    
    @Override
    public boolean onContextItemSelected(MenuItem item) {
        switch(item.getItemId()) {
        case ADDFAV_ID:
            AdapterContextMenuInfo info = (AdapterContextMenuInfo) item.getMenuInfo();
            // TODO 
            //mDbHelper.deleteNote(info.id);
            //fillData();
            return true;
        }
        return super.onContextItemSelected(item);
    }
    
    
    @Override
    protected void onListItemClick(ListView l, View v, int position, long id) {
    	super.onListItemClick(l, v, position, id);
        Cursor c = mCursor;
        c.moveToPosition(position);
        Intent i = new Intent(this, ViewStation.class);
        if (fromstation != null) {
        	i.putExtra("tostation", c.getString(c.getColumnIndexOrThrow("_id")));
        	i.putExtra("fromstation", fromstation);
        	i.putExtra("toname", c.getString(
                c.getColumnIndexOrThrow("name")));
        } else {
        	i.putExtra("_id", c.getString(c.getColumnIndexOrThrow("_id")));
        	i.putExtra("name", c.getString(
                c.getColumnIndexOrThrow("name")));
        }
        startActivityForResult(i, ACTIVITY_VIEWSTATION);
    }
    
    
    private void fillData(String search) {
        // Get all of the notes from the database and create the item list
    	
    	if (search == null) {
    		mCursor = mDbHelper.fetchAllStations();
    	} else {
    		mCursor = mDbHelper.searchStations(search);
    	}
        startManagingCursor(mCursor);

        String[] from = new String[] { "name" };
        int[] to = new int[] { R.id.text1 };
        
        // Now create an array adapter and set it to display using our row
        SimpleCursorAdapter notes =
            new SimpleCursorAdapter(this, R.layout.stations_row, mCursor, from, to);
        setListAdapter(notes);
    }
    

    
}