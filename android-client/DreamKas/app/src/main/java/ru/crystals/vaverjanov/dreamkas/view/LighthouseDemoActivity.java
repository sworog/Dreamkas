package ru.crystals.vaverjanov.dreamkas.view;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.Fragment;
import android.app.SearchManager;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.Uri;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.support.v4.app.ActionBarDrawerToggle;
import android.support.v4.view.GravityCompat;
import android.support.v4.widget.DrawerLayout;
import android.util.Log;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.Toast;

import com.octo.android.robospice.SpiceManager;
import com.octo.android.robospice.persistence.DurationInMillis;
import com.octo.android.robospice.persistence.exception.SpiceException;
import com.octo.android.robospice.request.listener.RequestListener;

import org.androidannotations.annotations.AfterViews;
import org.androidannotations.annotations.Bean;
import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EActivity;
import org.androidannotations.annotations.ItemClick;
import org.androidannotations.annotations.ViewById;
import org.springframework.util.StringUtils;

import ru.crystals.vaverjanov.dreamkas.R;
import ru.crystals.vaverjanov.dreamkas.controller.GetGroupsRequest;
import ru.crystals.vaverjanov.dreamkas.controller.LighthouseSpiceService;
import ru.crystals.vaverjanov.dreamkas.controller.listeners.GetGroupsRequestListener;
import ru.crystals.vaverjanov.dreamkas.model.DreamkasFragments;
import ru.crystals.vaverjanov.dreamkas.model.NamedObjects;

@EActivity(R.layout.activity_lighhouse_demo)
public class LighthouseDemoActivity extends Activity implements IChangeFragmentHandler
{
    @ViewById
    DrawerLayout drawer_layout;

    @ViewById
    ListView lstDrawer;

    ActionBarDrawerToggle drawerToggle;

    private SpiceManager spiceManager = new SpiceManager(LighthouseSpiceService.class);

    private String token;

    @Override
    protected void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_lighhouse_demo);

        Intent intent = getIntent();
        token = intent.getStringExtra("access_token");
    }

    @AfterViews
    void initDrawer()
    {
        drawer_layout.setDrawerShadow(R.drawable.drawer_shadow, GravityCompat.START);

        lstDrawer.setAdapter(new ArrayAdapter<String>(this, R.layout.drawer_list_item, getResources().getStringArray(R.array.views_array)));
        lstDrawer.setChoiceMode(ListView.CHOICE_MODE_SINGLE);
        lstDrawer.setSelection(0);

        getActionBar().setDisplayHomeAsUpEnabled(true);
        getActionBar().setHomeButtonEnabled(true);
        getActionBar().setTitle(getResources().getString(R.string.dream_kas_title));
        drawerToggle = new ActionBarDrawerToggle(this, drawer_layout, R.drawable.ic_drawer, R.string.drawer_open, R.string.drawer_close)
        {
            public void onDrawerClosed(View view)
            {
                getActionBar().setTitle(getResources().getString(R.string.dream_kas_title));
                invalidateOptionsMenu();
            }

            public void onDrawerOpened(View drawerView)
            {
                getActionBar().setTitle(getResources().getString(R.string.drawer_title));
                invalidateOptionsMenu();
            }
        };
        drawer_layout.setDrawerListener(drawerToggle);

        drawerToggle.syncState();

        displayView(0);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item)
    {
        if (drawerToggle.onOptionsItemSelected(item))
        {
            return true;
        }

        switch(item.getItemId())
        {
            default:
                return super.onOptionsItemSelected(item);
        }
    }

    @ItemClick
    void lstDrawerItemClicked(int position)
    {
        lstDrawer.setSelection(position);
        //todo change activity
        //Toast.makeText(this, position, Toast.LENGTH_LONG).show();
        displayView(position);
        //clipboardManager.setText(selectedBookmark.getUrl());
    }

    @Override
    public void onStart()
    {
        spiceManager.start(this);
        super.onStart();
    }

    @Override
    public void onStop()
    {
        spiceManager.shouldStop();
        super.onStop();
    }

    public void displayView(int position)
    {
        // update the main content by replacing fragments
        Fragment fragment = null;
        switch (position)
        {
            case 0:
                fragment = new KasFragment_();
                break;
            case 1:
                fragment = new StoreFragment_();
                break;
            case 2:
                exitConfirmation();

                break;
            default:
                break;
        }

        if (fragment != null)
        {
            android.app.FragmentManager fragmentManager = getFragmentManager();
            fragmentManager.beginTransaction().replace(R.id.content_frame, fragment).commit();

            lstDrawer.setItemChecked(position, true);
            lstDrawer.setSelection(position);
            //setTitle(viewsNames[position]);
            drawer_layout.closeDrawer(lstDrawer);
        } else
        {
            // error in creating fragment
            Log.e("MainActivity", "Error in creating fragment");
        }
    }

    private void exitConfirmation()
    {
        AlertDialog.Builder ad = new AlertDialog.Builder(this);

        ad.setTitle(getResources().getString(R.string.exitDialogTitle));
        ad.setMessage(getResources().getString(R.string.exitDialogMsg));
        ad.setPositiveButton(getResources().getString(R.string.Yes), new DialogInterface.OnClickListener()
        {
            public void onClick(DialogInterface dialog, int arg1)
            {
                logoff();
            }
        });

        ad.setNegativeButton(getResources().getString(R.string.No), new DialogInterface.OnClickListener()
        {
            public void onClick(DialogInterface dialog, int arg1)
            {}
        });

        ad.setCancelable(false);
        ad.show();
    }

    private void logoff()
    {
        token = null;
        Intent objsignOut = new Intent(getBaseContext(),LoginActivity_.class);
        startActivity(objsignOut);
        finish();
    }

    public String getToken() {
        return token;
    }

    @Override
    public void onFragmentChange(DreamkasFragments target)
    {
        displayView(target.getValue());
    }

    @Override
    public SpiceManager getRestClient()
    {
        return spiceManager;
    }
}
