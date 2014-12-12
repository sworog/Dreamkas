package ru.dreamkas.pos.espresso.steps;

import android.app.Activity;
import android.app.Instrumentation;
import android.content.res.Resources;

import com.google.android.apps.common.testing.testrunner.ActivityLifecycleMonitorRegistry;
import com.google.android.apps.common.testing.testrunner.Stage;

import org.hamcrest.Matchers;

import java.util.Collection;
import java.util.concurrent.Callable;
import java.util.concurrent.ExecutionException;
import java.util.concurrent.FutureTask;
import java.util.concurrent.RunnableFuture;

import ru.dreamkas.pos.R;

import static com.google.android.apps.common.testing.ui.espresso.Espresso.onView;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.doesNotExist;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.matches;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.assertThat;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isDescendantOfA;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isDisplayed;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isRoot;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withId;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withText;
import static org.hamcrest.CoreMatchers.allOf;
import static ru.dreamkas.pos.espresso.EspressoHelper.hasErrorText;
import static ru.dreamkas.pos.espresso.EspressoHelper.waitForView;
import static ru.dreamkas.pos.espresso.EspressoHelper.waitId;
import static ru.dreamkas.pos.espresso.EspressoHelper.withResourceName;

public class LoginThen {
    public static void checkEmptyCredentials() {
        //check error icon visibility inside edittext
        onView(withId(R.id.txtUsername)).check(matches(hasErrorText("Поле должно быть заполнено")));
        //check error icon visibility inside edittext
        onView(withId(R.id.txtPassword)).check(matches(hasErrorText("Поле должно быть заполнено")));
    }

    public static void checkSuccessAuth() {
        //check if loading dialog is displayed
        //onView(withText(R.string.auth_dialog_title)).check(matches(isDisplayed()));

        //check that loading dialog is dismissed
        //onView(withText(R.string.auth_dialog_title)).check(doesNotExist());

        //here we got new opened activity after login and here we should see action bar with title



        //android:id/", "action_bar_title
        //int actionBarTitleId = Resources.getSystem().getIdentifier("action_bar_title", "id", "android");
        //onView(isRoot()).perform(waitId(R.id.spStores, 10000));

        waitForView(R.id.spStores, 20000, isDisplayed());

        //onView(allOf(isDescendantOfA(withResourceName("android:id/action_bar_container")), withText(R.string.dream_kas_title)));
        onView(allOf(isDescendantOfA(withResourceName("android:id/action_bar_container")), withText("Смена магазина")));

        //onView(isRoot()).perform(waitId(R.id.spStores, 10000));

        //check that btnLogin does not exist in current context
        //onView(withId(R.id.btnLogin)).check(doesNotExist());

        //assertThat("Login activity doesn't destroy after login", authActivity.isFinishing(), Matchers.is(true));
    }

    public static void checkResumedActivitiesCount(Instrumentation instrumentation) throws ExecutionException, InterruptedException {
        RunnableFuture activityInStageGetterRunnable = new FutureTask(new Callable<Integer>()
        {
            @Override
            public Integer call() throws Exception
            {
                Collection<Activity> activities = ActivityLifecycleMonitorRegistry.getInstance().getActivitiesInStage(Stage.RESUMED);
                return activities.size();
            }
        });

        instrumentation.runOnMainSync(new Thread(activityInStageGetterRunnable));
        assertThat("StoreFragment does't exists in current activity",(Integer)activityInStageGetterRunnable.get(), Matchers.equalTo(1));


    }
}
