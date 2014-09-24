package ru.crystals.vaverjanov.dreamkas.espresso.helpers;

import android.content.Context;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.net.wifi.WifiManager;

public class NetworkManager
{
    private WifiManager mWifiManager;
    private Context mContext;

    public NetworkManager(Context context)
    {
        mContext = context;
        mWifiManager = (WifiManager)mContext.getSystemService(Context.WIFI_SERVICE);
    }

    public void changeWifiState(boolean state) throws InterruptedException
    {
        mWifiManager.setWifiEnabled(state);

        while (isConnected(mContext) != state)
        {
            Thread.sleep(1000);
        }
    }

    public static boolean isConnected(Context context)
    {
        ConnectivityManager connectivityManager = (ConnectivityManager)context.getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo networkInfo = null;
        if (connectivityManager != null)
        {
            networkInfo = connectivityManager.getActiveNetworkInfo();
        }
        return networkInfo != null && networkInfo.getState() == NetworkInfo.State.CONNECTED;
    }


}
