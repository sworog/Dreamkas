package ru.dreamkas.pos.espresso.steps;

import ru.dreamkas.pos.R;
import ru.dreamkas.pos.model.DrawerMenu;

import static com.google.android.apps.common.testing.ui.espresso.Espresso.onData;
import static com.google.android.apps.common.testing.ui.espresso.Espresso.onView;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.click;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.matches;
import static com.google.android.apps.common.testing.ui.espresso.contrib.DrawerActions.openDrawer;
import static com.google.android.apps.common.testing.ui.espresso.contrib.DrawerMatchers.isOpen;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withId;
import static org.hamcrest.CoreMatchers.instanceOf;
import static org.hamcrest.Matchers.allOf;
import static org.hamcrest.Matchers.is;

public class DrawerSteps {

    public static void select(DrawerMenu.AppStates state) {
        openDrawer(R.id.drawer_layout);
        onView(withId(R.id.drawer_layout)).check(matches(isOpen()));
        onData(allOf(is(instanceOf(Object.class)), is(DrawerMenu.getMenuItems().get(state)))).inAdapterView(withId(R.id.lstDrawer)).perform(click());
    }
}
