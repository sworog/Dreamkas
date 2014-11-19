package ru.dreamkas.pos.espresso.testSuites;

import android.app.Activity;
import android.app.Instrumentation;
import android.content.Context;
import android.content.SharedPreferences;
import android.preference.PreferenceManager;
import android.test.ActivityInstrumentationTestCase2;
import android.util.Log;

import com.google.android.apps.common.testing.testrunner.ActivityLifecycleMonitorRegistry;
import com.google.android.apps.common.testing.testrunner.Stage;

import ru.dreamkas.pos.espresso.ScreenshotFailureHandler;
import ru.dreamkas.pos.view.activities.LoginActivity_;

import static com.google.android.apps.common.testing.ui.espresso.Espresso.setFailureHandler;
import static com.google.common.collect.Iterables.getOnlyElement;

public abstract class BaseTestSuite<T extends Activity> extends ActivityInstrumentationTestCase2<T> {
    @SuppressWarnings("deprecation")
    protected BaseTestSuite(String pkg, Class<T> activityClass) {
        super(pkg, activityClass);
    }

    Activity getCurrentActivity() throws Throwable {
        getInstrumentation().waitForIdleSync();
        final Activity[] activity = new Activity[1];
        runTestOnUiThread(new Runnable() {
            @Override
            public void run() {
                java.util.Collection<Activity> activites = ActivityLifecycleMonitorRegistry.getInstance().getActivitiesInStage(Stage.RESUMED);
                activity[0] = getOnlyElement(activites);
            }});
        return activity[0];
    }

    @Override
    public void tearDown() throws Exception {
        super.tearDown();
    }


    @Override
    protected void setUp() throws Exception
    {
        super.setUp();
        clearPreferences();
        getActivity();
    }

    private void clearPreferences() {
        Instrumentation instrumentation = getInstrumentation();
        SharedPreferences preferences = PreferenceManager.getDefaultSharedPreferences(instrumentation.getTargetContext());
        preferences.edit().clear().commit();
    }
}
