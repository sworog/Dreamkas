package ru.crystals.vaverjanov.dreamkas.espresso.helpers;

import android.app.Activity;

public class FragmentHelper
{
    private final Activity mActivity;

    public FragmentHelper(Activity activity)
    {
        mActivity = activity;
    }

    Runnable fragmentPendingTransactionRunner;
    {
        fragmentPendingTransactionRunner = new Runnable()
        {
            @Override
            public void run() {
                android.app.FragmentManager fragmentManager = mActivity.getFragmentManager();
                fragmentManager.executePendingTransactions();
                synchronized (this)
                {
                    // This is a bug in IntelliJ. https://code.google.com/p/android/issues/detail?id=61512
                    this.notify();
                }
            }
        };
    }

    public void waitFragmentTransactionEnds() throws InterruptedException
    {
        synchronized( fragmentPendingTransactionRunner )
        {
            mActivity.runOnUiThread(fragmentPendingTransactionRunner) ;
            fragmentPendingTransactionRunner.wait() ;
        }
    }
}
