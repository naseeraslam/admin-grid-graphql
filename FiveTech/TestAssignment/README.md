## Overview

The FiveTech_TestAssignment module is a custom Magento 2 module developed to extend the functionality of your Magento store. This README file provides instructions on how to install and configure the module, including how to implement Access Control Lists (ACL) and assign roles and permissions, and how to use the Admin Grid for managing records.

### Step-by-Step Guide to Install FiveTech_TestAssignment Module

1. **Clone the repository or download the zip file:**

   - **Using Git Clone:**
     ```bash
     git clone https://github.com/naseeraslam/admin-grid-graphql.git app/code/
     ```
   - **Download as Zip:**
     - Download the zip file from the repository.
     - Unzip and extract it to `app/code/FiveTech/TestAssignment`.

### 2. Run Magento installation commands

After cloning or extracting the module, navigate to the root directory of your Magento installation and run the following commands to install the module:

#### Step 1: Upgrade the setup

```bash
bin/magento setup:upgrade
```

#### Step 2: Compile the dependency injection configurations

```bash
bin/magento setup:di:compile
```

#### Step 3: Flush the cache

```bash
bin/magento cache:flush
```

## Admin Grid

The FiveTech_TestAssignment module includes an Admin Grid for managing records, located under `Marketing > FiveTech Record`. The grid allows you to view, add, edit, delete, and perform inline edits on records. Pagination and sorting functionalities are also available.

### Features of the Admin Grid

1. **View Records**: The grid displays a list of all records with relevant data.
2. **Add New Record**: Click the `Add New` button to create a new record.
3. **Edit Record**: Click on a record to edit it. You can also perform inline edits directly on the grid.
4. **Delete Record**: Select a record and choose the `Delete` option to remove it.
5. **Save and Save & Continue**: Use the `Save` button to save changes, and the `Save & Continue` button to save and continue editing.
6. **Pagination and Sorting**: The grid supports pagination and sorting to manage large sets of data efficiently.

### Using the Admin Grid

1. **Navigate to the Grid**:

   - Go to the Magento Admin Panel.
   - Navigate to `Marketing` > `FiveTech Record`.

2. **Add New Record**:

   - Click the `Add New` button.
   - Fill in the required fields.
   - Click `Save` or `Save & Continue`.

3. **Edit Record**:

   - Click on a record to open the edit form.
   - Make the necessary changes.
   - Click `Save` or `Save & Continue`.

4. **Inline Edit**:

   - Double-click on a field in the grid to edit it directly.
   - Press `Enter` to save changes.

5. **Delete Record**:
   - Select the checkbox next to the record(s) you want to delete.
   - Select `Delete` from the `Actions` dropdown.
   - Confirm the deletion.

## Access Control List (ACL) Implementation

### ACL Configuration

The `acl.xml` file in the `etc` directory defines the permissions for various operations in the module. Below is an example of an `acl.xml` file:

```xml
<?xml version="1.0" ?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Acl/etc/acl.xsd">
    <acl>
        <resources>
            <resource id="Magento_Backend::admin">
                <resource id="FiveTech_TestAssignment::FiveTechs" title="FiveTechs" sortOrder="10">
                    <resource id="FiveTech_TestAssignment::FiveTechs_save" title="Save FiveTechs" sortOrder="10"/>
                    <resource id="FiveTech_TestAssignment::FiveTechs_delete" title="Delete FiveTechs" sortOrder="20"/>
                    <resource id="FiveTech_TestAssignment::FiveTechs_update" title="Update FiveTechs" sortOrder="30"/>
                    <resource id="FiveTech_TestAssignment::FiveTechs_view" title="View FiveTechs" sortOrder="40"/>
                </resource>
            </resource>
        </resources>
    </acl>
</config>
```

### Explanation of ACL Implementation

- **Resource IDs**: Each `resource` element has a unique `id` attribute that defines the permission. For example, `FiveTech_TestAssignment::FiveTechs_save` allows saving operations.
- **Title**: The `title` attribute specifies the name of the permission that will be displayed in the Magento Admin.
- **Sort Order**: The `sortOrder` attribute determines the order in which permissions are displayed.

### Assigning Roles and Permissions

To assign roles and permissions in Magento:

1. **Navigate to Roles**:

   - Go to the Magento Admin Panel.
   - Navigate to `System` > `Permissions` > `User Roles`.

2. **Create a New Role**:

   - Click on `Add New Role`.
   - Enter the `Role Name` and `Current Admin Password`.

3. **Define Role Resources**:

   - Go to the `Role Resources` tab.
   - Select `Custom` from the `Resource Access` dropdown.
   - Expand the `FiveTechs` section.
   - Check the permissions you want to assign (e.g., `Save FiveTechs`, `Delete FiveTechs`, etc.).

4. **Assign Role to Users**:
   - Navigate to `System` > `Permissions` > `All Users`.
   - Edit the user you want to assign the role to.
   - In the `User Role` section, select the role you created.
   - Save the user.

This GraphQL API allows querying and filtering of `FiveTech` entities. It provides two main query operations: `getFiveTechById` and `fiveTechs`. This document details the schema structure, query operations, input types, and sample queries to help developers integrate and utilize this API efficiently.

### Queries

#### 1. `getFiveTechById`

This query fetches a single `FiveTech` entity based on its ID.

**Arguments:**

- `fiveTechId` (Int!): The ID of the `FiveTech` entity to be retrieved.

**Sample Query:**

```graphql
query getFiveTechById {
  getFiveTechById(fiveTechId: 77) {
    entity_id
    name
    description
    country
    created_at
    updated_at
  }
}
```

#### 2. `fiveTechs`

This query fetches a collection of `FiveTech` entities based on specified filters, pagination, and sorting criteria.

**Arguments:**

- `filters` (FiveTechFilterInput): Defines the attributes to filter the search results.
- `pageSize` (Int): Specifies the maximum number of results to return at once. Default is 20.
- `currentPage` (Int): Specifies which page of results to return. Default is 1.
- `sort` (FiveTechsortInput): Specifies the attributes to sort the search results.

**Sample Query:**

```graphql
query fiveTechs {
    fiveTechs(
        filters: {
            description: {
                match: "ABCD"
            }
        },
        pageSize: 1,
        currentPage: 1,
        sort: {
            # Add sorting criteria here if needed
        }
    ) {
        items {
            entity_id
            name
            description
            country
            created_at
            updated_at
        }
        page_info {
            current_page
            page_size
            total_pages
        }
        total_count
    }
}
```

### Schema Details

#### Query Type

The `Query` type defines the entry points for the API.

```graphql
type Query {
  getFiveTechById(
    fiveTechId: Int! @doc(description: "Id of FiveTech.")
  ): [FiveTech]
    @resolver(
      class: "FiveTech\\TestAssignment\\Model\\Resolver\\FiveTech\\FindById"
    )

  fiveTechs(
    filters: FiveTechFilterInput
      @doc(
        description: "Identifies which FiveTech filter inputs to search for and return."
      )
    pageSize: Int = 20
      @doc(
        description: "Specifies the maximum number of results to return at once. This attribute is optional."
      )
    currentPage: Int = 1
      @doc(
        description: "Specifies which page of results to return. The default value is 1."
      )
    sort: FiveTechsortInput
      @doc(
        description: "Specifies which attributes to sort on, and whether to return the results in ascending or descending order."
      )
  ): FiveTechs
    @resolver(
      class: "\\FiveTech\\TestAssignment\\Model\\Resolver\\FiveTech\\FilterBySearchCriteria"
    )
    @doc(
      description: "The FiveTech query searches that match the criteria specified in the search and filter attributes."
    )
}
```

#### FiveTechs Type

The `FiveTechs` type represents a collection of `FiveTech` entities and includes pagination metadata.

```graphql
type FiveTechs
  @doc(
    description: "The collection of FiveTechs that match the conditions defined in the filter."
  ) {
  items: [FiveTech]! @doc(description: "An array of FiveTechs.")
  page_info: SearchResultPageInfo
    @doc(description: "Contains pagination metadata.")
  total_count: Int @doc(description: "The total count of FiveTechs.")
}
```

#### FiveTechFilterInput

The `FiveTechFilterInput` input type specifies the attributes and values used for filtering search results.

```graphql
input FiveTechFilterInput
  @doc(
    description: "FiveTechInput defines the filters to be used in the search. A filter contains at least one attribute, a comparison operator, and the value that is being searched for."
  ) {
  entity_id: FilterEqualTypeInput
    @deprecated(reason: "Filter FiveTech entity_id")
  name: FilterMatchTypeInput @deprecated(reason: "Filter FiveTech name")
  description: FilterMatchTypeInput
    @deprecated(reason: "Filter FiveTech description")
  country: FilterMatchTypeInput @deprecated(reason: "Filter FiveTech country")
  created_at: FilterMatchTypeInput
    @deprecated(reason: "Filter FiveTech created_at")
  updated_at: FilterMatchTypeInput
    @deprecated(reason: "Filter FiveTech updated_at")
}
```

#### FiveTechsortInput

The `FiveTechsortInput` input type specifies the attributes used for sorting search results.

```graphql
input FiveTechsortInput
  @doc(
    description: "FiveTechsortInput specifies the attribute to use for sorting search results and indicates whether the results are sorted in ascending or descending order."
  ) {
  entity_id: SortEnum @deprecated(reason: "Filter FiveTech FiveTech_id")
  search_page: SortEnum @deprecated(reason: "Filter FiveTech search_page")
}
```

#### FiveTech Type

The `FiveTech` type represents the entity with its attributes.

```graphql
type FiveTech {
  entity_id: Int!
  name: String
  description: String
  country: String
  created_at: String
  updated_at: String
}
```

### Pagination and Sorting

The `page_info` field in the `FiveTechs` type contains metadata about the pagination of the results, including the current page, page size, and total number of pages.

**Sample Pagination Info:**

```graphql
page_info {
    current_page
    page_size
    total_pages
}
```

The `sort` input allows specifying attributes for sorting the search results in either ascending or descending order.

### Filters

The `FiveTechFilterInput` allows filtering the search results based on various attributes like `entity_id`, `name`, `description`, `country`, `created_at`, and `updated_at`. Each filter is marked as deprecated to signal that these are legacy filters and may be replaced in future iterations.

### Usage

To use this API, define the queries as shown in the sample queries section. Adjust the filters, pagination, and sorting criteria as per your requirements.

- Use `getFiveTechById` to fetch a single `FiveTech` entity by its ID.
- Use `fiveTechs` to fetch a collection of `FiveTech` entities with optional filters, pagination, and sorting.

Documentation provides an overview of the schema and how to use the queries. For more details on GraphQL and implementing resolvers, refer to the official GraphQL documentation.

## Support

For support, please contact the module developer at [naseeraslamkhan016@gmail.com](mailto:naseeraslamkhan016@gmail.com) or raise an issue in the repository.

## License

This module is licensed under the MIT License. See the LICENSE file for more details.

```

This README file now includes the GitHub repository URL, detailed instructions on ACL and role assignment, and comprehensive guidance on using the Admin Grid.
```
