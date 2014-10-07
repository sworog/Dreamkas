package ru.crystals.vaverjanov.dreamkas.espresso.helpers;

import android.content.Context;
import android.content.Intent;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.net.wifi.WifiManager;
import android.provider.Settings;
import android.util.Log;

import java.lang.reflect.Field;
import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;

public class NetworkManager
{
    private WifiManager mWifiManager;
    private ConnectivityManager mConnectivityManager;
    private Context mContext;

    public NetworkManager(Context context)
    {
        mContext = context;
        mWifiManager = (WifiManager)mContext.getSystemService(Context.WIFI_SERVICE);
        mConnectivityManager = (ConnectivityManager)mContext.getSystemService(Context.CONNECTIVITY_SERVICE);
    }

    public void changeNetworkState(boolean state) throws InterruptedException, ClassNotFoundException, NoSuchMethodException, InvocationTargetException, IllegalAccessException, NoSuchFieldException
    {
        changeWifiState(state);
        //changeMobileDataState(state);

        //3g on emu always on connection
        //while (isConnected() != state)
        if(state)
        {
            while (WifiManager.WIFI_STATE_ENABLED != mWifiManager.getWifiState())
            {
                Thread.sleep(1000);
            }
        }else
        {
            while (WifiManager.WIFI_STATE_DISABLED != mWifiManager.getWifiState())
            {
                Thread.sleep(1000);
            }
        }
    }

    private void changeWifiState(boolean state) throws InterruptedException
    {
        mWifiManager.setWifiEnabled(state);
    }

    //not work in 4.4.2
   /* private void changeMobileDataState(boolean state) throws ClassNotFoundException, NoSuchFieldException, IllegalAccessException, NoSuchMethodException, InvocationTargetException
    {
        final Class conmanClass = Class.forName(mConnectivityManager.getClass().getName());
        final Field iConnectivityManagerField = conmanClass.getDeclaredField("mService");
        iConnectivityManagerField.setAccessible(true);
        final Object iConnectivityManager = iConnectivityManagerField.get(mConnectivityManager);
        final Class iConnectivityManagerClass = Class.forName(iConnectivityManager.getClass().getName());
        final Method setMobileDataEnabledMethod = iConnectivityManagerClass.getDeclaredMethod("setMobileDataEnabled", Boolean.TYPE);
        setMobileDataEnabledMethod.setAccessible(true);

        setMobileDataEnabledMethod.invoke(iConnectivityManager, state);
    }*/

    public boolean isConnected()
    {
        NetworkInfo networkInfo = null;
        if (mConnectivityManager != null)
        {
            networkInfo = mConnectivityManager.getActiveNetworkInfo();
        }
        return networkInfo != null && networkInfo.getState() == NetworkInfo.State.CONNECTED;
    }
}
