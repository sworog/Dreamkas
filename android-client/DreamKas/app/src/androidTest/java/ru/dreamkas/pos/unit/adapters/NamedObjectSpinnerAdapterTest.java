package ru.dreamkas.pos.unit.adapters;

import android.test.AndroidTestCase;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import org.hamcrest.Matchers;

import java.util.ArrayList;

import ru.dreamkas.pos.R;
import ru.dreamkas.pos.adapters.NamedObjectSpinnerAdapter;
import ru.dreamkas.pos.adapters.ProductsAdapter;
import ru.dreamkas.pos.adapters.ReceiptAdapter;
import ru.dreamkas.pos.model.api.NamedObject;
import ru.dreamkas.pos.model.api.Product;

import static org.hamcrest.CoreMatchers.allOf;
import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class NamedObjectSpinnerAdapterTest extends AndroidTestCase {
    private NamedObjectSpinnerAdapter mAdapter;
    private NamedObject mNamedObject;

    public NamedObjectSpinnerAdapterTest() {
        super();
    }

    protected void setUp() throws Exception {
        super.setUp();
        ArrayList<NamedObject> data = new ArrayList<NamedObject>();

        mNamedObject = new NamedObject("someTestId","test named object name");
        data.add(mNamedObject);
        mAdapter = new NamedObjectSpinnerAdapter(getContext(), android.R.layout.simple_spinner_item, android.R.layout.simple_spinner_dropdown_item, data);
    }

    public void testGetItem() {
        assertThat("product 'test1' was expected.", mNamedObject.getName(),is((mAdapter.getItem(0)).getName()));
    }

    public void testGetCount() {
        assertThat("NamedObjects count incorrect.", (int)1, allOf(is(mAdapter.getItems().size()),is(mAdapter.getCount())));
    }

    public void testGetHintItem() {
        String originHintText = getContext().getResources().getString(R.string.msgSelectedNullStore);
        String adapterHintText = mAdapter.getItem(mAdapter.getHintElementIndex()).getName();

        assertThat("Hint text incorrect.", originHintText, is(adapterHintText));
    }

    public void testGetView() {
        View view = mAdapter.getView(0, null, null);
        assertThat("View is null.", view, Matchers.notNullValue());
    }

    public void testGetDropDownView() {
        View view = mAdapter.getDropDownView(0, null, null);
        assertThat("DropDownView is null.", view, Matchers.notNullValue());
    }

    public void testItemViewContentTitle(){
        View view = mAdapter.getView(0, null, null);
        TextView title = (TextView) view.findViewById(android.R.id.text1);
        assertThat("Title doesn't match.", mNamedObject.getName(), is(title.getText()));
    }

    public void testItemDropDownViewContentTitle(){
        View view = mAdapter.getDropDownView(0, null, null);
        TextView title = (TextView) view.findViewById(android.R.id.text1);
        assertThat("DropDownTitle doesn't match.", mNamedObject.getName(), is(title.getText()));
    }
}
