package ru.dreamkas.pos.espresso.steps;


import android.view.View;

import com.google.android.apps.common.testing.ui.espresso.action.ViewActions;

import org.hamcrest.Matcher;

import ru.dreamkas.pos.R;

import static com.google.android.apps.common.testing.ui.espresso.Espresso.onView;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.clearText;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.click;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.closeSoftKeyboard;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.typeText;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isDisplayed;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withId;
import static ru.dreamkas.pos.espresso.EspressoHelper.waitForView;
import static ru.dreamkas.pos.espresso.EspressoHelper.waitKeyboard;

public class LoginSteps {
    public static void enterCredentialsAndClick(String userName, String password)
    {
        waitForView(R.id.txtUsername, 5000, isDisplayed());
        onView(withId(R.id.txtUsername)).perform(clearText()).perform(typeText(userName), closeSoftKeyboard());

        //hack, espresso bug here. without waiting cause exception
        waitKeyboard(200);

        waitForView(R.id.txtPassword, 5000, isDisplayed());
        onView(withId(R.id.txtPassword)).perform(clearText()).perform(typeText(password), closeSoftKeyboard());

        //hack, espresso bug here. without waiting cause exception
        waitKeyboard(200);

        //click on login button
        waitForView(R.id.btnLogin, 5000, isDisplayed());
        onView(withId(R.id.btnLogin)).perform(click());


    }
}
