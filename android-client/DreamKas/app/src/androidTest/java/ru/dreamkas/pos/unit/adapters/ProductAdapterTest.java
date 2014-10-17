package ru.dreamkas.pos.unit.adapters;

import android.test.AndroidTestCase;
import android.view.View;
import android.widget.TextView;

import org.hamcrest.Matchers;

import java.util.ArrayList;

import ru.dreamkas.pos.R;
import ru.dreamkas.pos.adapters.ProductsAdapter;
import ru.dreamkas.pos.adapters.ReceiptAdapter;
import ru.dreamkas.pos.model.api.Product;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class ProductAdapterTest extends AndroidTestCase {
    private ProductsAdapter mAdapter;
    private Product product1;

    public ProductAdapterTest() {
        super();
    }

    protected void setUp() throws Exception {
        super.setUp();
        ArrayList<Product> data = new ArrayList<Product>();

        product1 = new Product();
        product1.setName("test1");
        product1.setSku("10002");

        Product product2 = new Product();
        product2.setName("test2");
        product2.setSku("10003");

        data.add(product1);
        data.add(product2);

        mAdapter = new ProductsAdapter(getContext(), R.layout.arrow_listview_item, data);
    }

    public void testGetItem() {
        assertEquals("product 'test1' was expected.", product1.getName(),(mAdapter.getItem(0)).getName());
    }

    public void testGetCount() {
        assertEquals("Products amount incorrect.", 2, mAdapter.getCount());
    }

    public void testGetView() {
        View view = mAdapter.getView(0, null, null);
        assertThat("View is null.", view, Matchers.notNullValue());
    }

    public void testItemViewContentTitle(){
        View view = mAdapter.getView(0, null, null);

        TextView title = (TextView) view.findViewById(R.id.txtListItemTitle);

        String shouldBe = String.format("%s / %s", product1.getName(), product1.getSku());

        assertThat("Title doesn't match.", shouldBe, is(title.getText()));
    }
}
