package ru.crystals.vaverjanov.dreamkas.view;

import android.app.Activity;
import android.content.SharedPreferences;
import android.net.Uri;
import android.os.Bundle;
import android.app.Fragment;
//import android.support.v4.app.Fragment;
import android.preference.PreferenceManager;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import android.widget.Toast;

import org.androidannotations.annotations.EFragment;
import org.androidannotations.annotations.ViewById;
import org.springframework.util.StringUtils;

import ru.crystals.vaverjanov.dreamkas.R;
import ru.crystals.vaverjanov.dreamkas.controller.PreferencesManager;
import ru.crystals.vaverjanov.dreamkas.model.DreamkasFragments;

@EFragment(R.layout.fragment_kas)
public class KasFragment extends BaseFragment
{
    private PreferencesManager preferences;

    @ViewById
    TextView lblStore;

    @Override
    public void onStart()
    {
        super.onStart();

        preferences = PreferencesManager.getInstance();

        if(StringUtils.hasText(preferences.getCurrentStore()))
        {

            lblStore.setText(preferences.getCurrentStore());
        }
        else
        {
            changeFragmentCallback.onFragmentChange(DreamkasFragments.Store);
        }

    }


}
