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

public class Selections extends ListActivity {
	private static final int ACTIVITY_VIEWSELECTION=0;
	private static final int ACTIVITY_ADDNEW=1;
	public static final int NEW_ID = Menu.FIRST;
	private static final int DELFAV_ID = Menu.FIRST + 1;

    private Cursor mCursor;
    
	
	private TimetableDbAdapter mDbHelper;
	

	
	private String fromstation; // this is filled if ViewStation activity calls us to select 
								// a destination station

	/** Called when the activity is first created. */
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.selections_list);
		mDbHelper = new TimetableDbAdapter(this);
		mDbHelper.open();
		fillData(null);
		
		registerForContextMenu(getListView());	
		setTitle(R.string.selections);
	}
    
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        boolean result = super.onCreateOptionsMenu(menu);
        menu.add(0, NEW_ID, 0, R.string.newsel);
        return result;
    }    

    @Override
	public void onCreateContextMenu(ContextMenu menu, View v,
			ContextMenuInfo menuInfo) {
		super.onCreateContextMenu(menu, v, menuInfo);
        menu.add(0, DELFAV_ID, 0, R.string.delete);
	}
    
    @Override
    public boolean onContextItemSelected(MenuItem item) {
        switch(item.getItemId()) {
        case DELFAV_ID:
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
       	i.putExtra("tostation", c.getString(c.getColumnIndexOrThrow("tostation")));
       	i.putExtra("fromstation", c.getString(c.getColumnIndexOrThrow("fromstation")));
       	i.putExtra("toname", mDbHelper.getStationName(c.getString(c.getColumnIndexOrThrow("tostation") ) ));
        startActivityForResult(i, ACTIVITY_VIEWSELECTION);
    }
    
    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
        case NEW_ID:
            addNew();
            return true;
        }
       
        return super.onOptionsItemSelected(item);
    }
    
    public void addNew() {
    	Intent i = new Intent(this, vlakic.class);      
        i.putExtra("force", "yes");   
        startActivityForResult(i, ACTIVITY_ADDNEW);	
    }
    
    private void fillData(String search) {
        // Get all of the notes from the database and create the item list
    	mCursor = mDbHelper.fetchAllSelections();
        startManagingCursor(mCursor);

        String[] from = new String[] { "name" };
        int[] to = new int[] { R.id.text1 };
        
        // Now create an array adapter and set it to display using our row
        SimpleCursorAdapter notes =
            new SimpleCursorAdapter(this, R.layout.selections_row, mCursor, from, to);
        setListAdapter(notes);
    }
    

    
}