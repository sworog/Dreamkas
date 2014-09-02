package ru.crystals.vaverjanov.dreamkas.espresso;

import android.test.ActivityInstrumentationTestCase2;
import android.widget.TextView;

import ru.crystals.vaverjanov.dreamkas.R;
import ru.crystals.vaverjanov.dreamkas.StartActivity;

import static android.test.ViewAsserts.assertOnScreen;
import static com.google.android.apps.common.testing.ui.espresso.Espresso.onView;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.matches;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withId;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withText;

public class StartActivityInstrumentationTest extends ActivityInstrumentationTestCase2<StartActivity> {
    private StartActivity mStartActivity;
    private TextView mHelloWorldTextView;

    @SuppressWarnings( "deprecation" )
    public StartActivityInstrumentationTest()
    {
        super("com.dropsport.espressoreadyproject", StartActivity.class);
    }

    protected void setUp() throws Exception
    {
        super.setUp();

        mStartActivity = getActivity();
        mHelloWorldTextView = (TextView) mStartActivity.findViewById(R.id.txtHelloWorld);
    }

    public void testTextView()
    {
        assertOnScreen(mStartActivity.getWindow().getDecorView(), mHelloWorldTextView);
    }

    public void testTextViewTrue()
    {
        onView(withId(R.id.txtHelloWorld)).check(matches(withText("Hello world!")));
    }

    public void testTextViewFalse()
    {
        onView(withId(R.id.txtHelloWorld2)).check(matches(withText("Hello world21")));
    }
}