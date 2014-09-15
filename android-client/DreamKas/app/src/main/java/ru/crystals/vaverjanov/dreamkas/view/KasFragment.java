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

@EFragment(R.layout.fragment_kas)
public class KasFragment extends BaseFragment
{
    @ViewById
    TextView lblStore;

    @Override
    public void onStart()
    {
        super.onStart();

        SharedPreferences preferences = PreferenceManager.getDefaultSharedPreferences(getActivity());
        String currentStoreId = preferences.getString(getResources().getString(R.string.current_store_id), "");

        if(StringUtils.hasText(currentStoreId))
        {

            lblStore.setText(currentStoreId);
        }
        else
        {
            changeFragmentCallback.onFragmentChange(KasFragments.Store);
        }

    }


}
