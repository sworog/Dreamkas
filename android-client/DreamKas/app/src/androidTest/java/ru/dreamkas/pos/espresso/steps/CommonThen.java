package ru.dreamkas.pos.espresso.steps;

import android.app.Activity;

import ru.dreamkas.pos.R;

import static com.google.android.apps.common.testing.ui.espresso.Espresso.onView;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.matches;
import static com.google.android.apps.common.testing.ui.espresso.matcher.RootMatchers.withDecorView;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isDisplayed;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withId;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withText;
import static org.hamcrest.core.Is.is;
import static org.hamcrest.core.IsNot.not;
import static ru.dreamkas.pos.espresso.EspressoHelper.waitForView;

public class CommonThen {
    public static void checkToast(String text, Activity activity) {
        //expect toast with error message
        final long startTime = System.currentTimeMillis();
        final long endTime = startTime + 20000;

        do {
            try {
                onView(withText(text)).inRoot(withDecorView(not(is(activity.getWindow().getDecorView())))).check(matches(isDisplayed()));
                return;
            } catch (Throwable ex) {
                Thread.yield();
            }
        } while (System.currentTimeMillis() < endTime);

        onView(withText(text)).inRoot(withDecorView(not(is(activity.getWindow().getDecorView())))).check(matches(isDisplayed()));
    }
}
