package ru.dreamkas.pos.espresso;

import android.app.Activity;
import android.content.Context;
import android.view.View;

import com.google.android.apps.common.testing.ui.espresso.FailureHandler;
import com.google.android.apps.common.testing.ui.espresso.base.DefaultFailureHandler;
import com.squareup.spoon.Spoon;

import org.hamcrest.Matcher;

public class ScreenshotFailureHandler implements FailureHandler {
    private final FailureHandler delegate;
    private final Context mContext;
    private final Activity mActivity;


    public ScreenshotFailureHandler(Context targetContext, Activity activity)
    {
        delegate = new DefaultFailureHandler(targetContext);
        mContext = targetContext;
        mActivity = activity;
    }

    @Override
    public void handle(Throwable error, Matcher<View> viewMatcher)
    {
        Spoon.screenshot(mActivity, "error");
        delegate.handle(error, viewMatcher);
        /*if (mContext instanceof Activity)
        {
            Spoon.screenshot((Activity) mContext, "error");
        }*/
    }
}
