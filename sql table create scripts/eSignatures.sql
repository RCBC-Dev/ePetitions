USE [PHPLogin_Dev]
GO
	/****** Object:  Table [dbo].[eSignatures]    Script Date: 17/09/2021 08:38:22 ******/
SET
	ANSI_NULLS ON
GO
SET
	QUOTED_IDENTIFIER ON
GO
	CREATE TABLE [dbo].[eSignatures](
		[sigid] [int] IDENTITY(1, 1) NOT NULL,
		[petid] [int] NOT NULL,
		[name] [nvarchar](300) NOT NULL,
		[email] [nvarchar](255) NOT NULL,
		[address] [nvarchar](300) NOT NULL,
		[postcode] [nvarchar](25) NOT NULL,
		[connection] [nvarchar](25) NOT NULL,
		[signeddate] [datetime] NOT NULL,
		[verifieddate] [datetime] NULL,
		[activatekey] [nvarchar](50) NULL,
		[status] [nvarchar](25) NOT NULL,
		[reason] [nvarchar](25) NULL
	) ON [PRIMARY]
GO